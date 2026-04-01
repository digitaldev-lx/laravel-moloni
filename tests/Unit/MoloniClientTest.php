<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Enums\AuthError;
use DigitaldevLx\LaravelMoloni\Events\TokenRefreshed;
use DigitaldevLx\LaravelMoloni\Exceptions\AuthenticationException;
use DigitaldevLx\LaravelMoloni\Exceptions\MoloniException;
use DigitaldevLx\LaravelMoloni\Exceptions\RateLimitException;
use DigitaldevLx\LaravelMoloni\Exceptions\ValidationException;
use DigitaldevLx\LaravelMoloni\Http\MoloniClient;
use DigitaldevLx\LaravelMoloni\Models\MoloniToken;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

/** @covers MoloniClient */
describe('MoloniClient', function (): void {
    function makeClient(): MoloniClient
    {
        return new MoloniClient(
            clientId: 'test-id',
            clientSecret: 'test-secret',
            username: 'test@example.com',
            password: 'test-pass',
        );
    }

    it('authenticates with password grant and persists tokens', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
        ]);

        $client = makeClient();
        $client->authenticate();

        Http::assertSent(function ($request): bool {
            return str_contains((string) $request->url(), 'grant/')
                && $request['grant_type'] === 'password'
                && $request['client_id'] === 'test-id'
                && $request['client_secret'] === 'test-secret'
                && $request['username'] === 'test@example.com'
                && $request['password'] === 'test-pass';
        });

        $this->assertDatabaseCount('moloni_tokens', 1);
    });

    it('sends post requests with access token in query string', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'my-access-token',
                'refresh_token' => 'my-refresh-token',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/getAll/*' => Http::response([
                ['customer_id' => 1, 'name' => 'Test Customer'],
            ]),
        ]);

        $client = makeClient();
        $result = $client->post('customers/getAll', ['company_id' => 1]);

        Http::assertSent(function ($request): bool {
            return str_contains((string) $request->url(), 'customers/getAll/')
                && str_contains((string) $request->url(), 'access_token=');
        });

        expect($result)->toBe([['customer_id' => 1, 'name' => 'Test Customer']]);
    });

    it('refreshes token on 401 response and retries the request', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::sequence()
                ->push(['access_token' => 'initial-token', 'refresh_token' => 'initial-refresh', 'expires_in' => 3600])
                ->push(['access_token' => 'refreshed-token', 'refresh_token' => 'new-refresh', 'expires_in' => 3600]),
            'api.moloni.pt/v1/products/getAll/*' => Http::sequence()
                ->push(null, 401)
                ->push([['product_id' => 1, 'name' => 'Widget']]),
        ]);

        $client = makeClient();
        $result = $client->post('products/getAll', ['company_id' => 1]);

        expect($result)->toBe([['product_id' => 1, 'name' => 'Widget']]);
    });

    it('throws AuthenticationException with AuthError enum when auth fails', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'error' => 'invalid_client',
                'error_description' => 'The client id is invalid',
            ], 400),
        ]);

        $client = makeClient();

        try {
            $client->authenticate();
            $this->fail('Expected AuthenticationException');
        } catch (AuthenticationException $e) {
            expect($e->authError)->toBe(AuthError::InvalidClient)
                ->and($e->errorDescription)->toBe('The client id is invalid')
                ->and($e->getMessage())->toContain('invalid_client');
        }
    });

    it('throws AuthenticationException with InvalidGrant on bad credentials', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'error' => 'invalid_grant',
                'error_description' => 'Invalid credentials',
            ], 400),
        ]);

        $client = makeClient();

        try {
            $client->authenticate();
            $this->fail('Expected AuthenticationException');
        } catch (AuthenticationException $e) {
            expect($e->authError)->toBe(AuthError::InvalidGrant)
                ->and($e->errorDescription)->toBe('Invalid credentials');
        }
    });

    it('throws ValidationException when API returns validation errors', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/insert/*' => Http::response([
                'vat' => [8],
                'name' => [1],
                'email' => [3],
            ]),
        ]);

        $client = makeClient();

        try {
            $client->post('customers/insert', ['company_id' => 1, 'vat' => 'invalid']);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            expect($e->errors)->toHaveCount(3)
                ->and($e->hasFieldError('vat'))->toBeTrue()
                ->and($e->hasFieldError('name'))->toBeTrue()
                ->and($e->hasFieldError('email'))->toBeTrue()
                ->and($e->getFieldErrors())->toHaveKey('vat')
                ->and($e->getFieldErrors()['vat'])->toContain('NIF');
        }
    });

    it('throws RateLimitException on 429 response', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/getAll/*' => Http::response(null, 429),
        ]);

        $client = makeClient();

        expect(fn () => $client->post('invoices/getAll', ['company_id' => 1]))
            ->toThrow(RateLimitException::class, 'rate limit exceeded');
    });

    it('throws MoloniException on other server errors', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/products/getAll/*' => Http::response(['error' => 'Internal Server Error'], 500),
        ]);

        $client = makeClient();

        expect(fn () => $client->post('products/getAll', ['company_id' => 1]))
            ->toThrow(MoloniException::class);
    });

    it('loads a valid non-expired token from storage without re-authenticating', function (): void {
        MoloniToken::query()->create([
            'access_token' => 'stored-token',
            'refresh_token' => 'stored-refresh',
            'expires_at' => now()->addHour(),
        ]);

        Http::fake([
            'api.moloni.pt/v1/customers/getAll/*' => Http::response([
                ['customer_id' => 42, 'name' => 'Stored Token Customer'],
            ]),
        ]);

        $client = makeClient();
        $result = $client->post('customers/getAll', ['company_id' => 1]);

        expect($result)->toBe([['customer_id' => 42, 'name' => 'Stored Token Customer']]);

        Http::assertNotSent(function ($request): bool {
            return str_contains((string) $request->url(), 'grant/');
        });
    });

    it('ignores an expired token in storage and re-authenticates', function (): void {
        MoloniToken::query()->create([
            'access_token' => 'expired-token',
            'refresh_token' => 'expired-refresh',
            'expires_at' => now()->subHour(),
        ]);

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'fresh-token',
                'refresh_token' => 'fresh-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/getAll/*' => Http::response([
                ['customer_id' => 1, 'name' => 'Fresh Auth Customer'],
            ]),
        ]);

        $client = makeClient();
        $client->post('customers/getAll', ['company_id' => 1]);

        Http::assertSent(function ($request): bool {
            return str_contains((string) $request->url(), 'grant/');
        });
    });

    it('dispatches TokenRefreshed event after a successful token refresh', function (): void {
        Event::fake();

        MoloniToken::query()->create([
            'access_token' => 'old-token',
            'refresh_token' => 'old-refresh',
            'expires_at' => now()->addHour(),
        ]);

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'refreshed-token',
                'refresh_token' => 'new-refresh',
                'expires_in' => 3600,
            ]),
        ]);

        $client = makeClient();
        $client->refreshAccessToken();

        Event::assertDispatched(TokenRefreshed::class);
    });
});
