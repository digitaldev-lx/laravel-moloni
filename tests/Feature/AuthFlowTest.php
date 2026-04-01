<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Events\TokenRefreshed;
use DigitaldevLx\LaravelMoloni\Http\MoloniClient;
use DigitaldevLx\LaravelMoloni\Models\MoloniToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

/** @covers MoloniClient */
describe('Auth flow', function (): void {
    function makeAuthClient(): MoloniClient
    {
        return new MoloniClient(
            clientId: 'test-client-id',
            clientSecret: 'test-client-secret',
            username: 'test@example.com',
            password: 'test-password',
        );
    }

    it('authenticates and persists token to database', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'fresh-access-token',
                'refresh_token' => 'fresh-refresh-token',
                'expires_in' => 3600,
            ]),
        ]);

        $client = makeAuthClient();
        $client->authenticate();

        $this->assertDatabaseCount('moloni_tokens', 1);

        /** @var MoloniToken $stored */
        $stored = MoloniToken::query()->first();

        expect($stored->access_token)->toBe('fresh-access-token')
            ->and($stored->refresh_token)->toBe('fresh-refresh-token')
            ->and($stored->expires_at->isFuture())->toBeTrue();
    });

    it('refreshes an expired token using the refresh_token grant', function (): void {
        Event::fake();

        // Create the initial token in the past so the new one gets a later timestamp
        Carbon::setTestNow(now()->subSecond());

        MoloniToken::query()->create([
            'access_token' => 'initial-access',
            'refresh_token' => 'initial-refresh',
            'expires_at' => now()->addHour(),
        ]);

        Carbon::setTestNow(); // reset to real time

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'new-access-token',
                'refresh_token' => 'new-refresh-token',
                'expires_in' => 3600,
            ]),
        ]);

        $client = makeAuthClient();
        $client->refreshAccessToken();

        Http::assertSent(function ($request): bool {
            return str_contains((string) $request->url(), 'grant/')
                && $request['grant_type'] === 'refresh_token'
                && $request['refresh_token'] === 'initial-refresh';
        });

        Event::assertDispatched(TokenRefreshed::class);

        $this->assertDatabaseCount('moloni_tokens', 2);

        /** @var MoloniToken $latest */
        $latest = MoloniToken::current();

        expect($latest->access_token)->toBe('new-access-token');
    });

    it('re-authenticates with password grant when refresh token is absent', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'reauth-access-token',
                'refresh_token' => 'reauth-refresh-token',
                'expires_in' => 3600,
            ]),
        ]);

        // No token in DB so refreshToken is null
        $client = makeAuthClient();
        $client->refreshAccessToken();

        Http::assertSent(function ($request): bool {
            return str_contains((string) $request->url(), 'grant/')
                && $request['grant_type'] === 'password';
        });

        $this->assertDatabaseCount('moloni_tokens', 1);
    });

    it('re-authenticates with password grant when refresh grant fails', function (): void {
        // Create the initial token in the past so the new one gets a later timestamp
        Carbon::setTestNow(now()->subSecond());

        MoloniToken::query()->create([
            'access_token' => 'old-access',
            'refresh_token' => 'old-refresh',
            'expires_at' => now()->addHour(),
        ]);

        Carbon::setTestNow(); // reset to real time

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::sequence()
                ->push(['error' => 'invalid_grant'], 400)
                ->push(['access_token' => 'fallback-token', 'refresh_token' => 'fallback-refresh', 'expires_in' => 3600]),
        ]);

        $client = makeAuthClient();
        $client->refreshAccessToken();

        // Two calls to grant/: first the failed refresh, then the password grant
        Http::assertSentCount(2);

        $this->assertDatabaseCount('moloni_tokens', 2);

        /** @var MoloniToken $latest */
        $latest = MoloniToken::current();

        expect($latest->access_token)->toBe('fallback-token');
    });

    it('loads a valid stored token on construction without hitting the auth endpoint', function (): void {
        MoloniToken::query()->create([
            'access_token' => 'cached-token',
            'refresh_token' => 'cached-refresh',
            'expires_at' => now()->addHour(),
        ]);

        Http::fake([
            'api.moloni.pt/v1/companies/getAll/*' => Http::response([
                ['company_id' => 1, 'name' => 'Loaded From Cache'],
            ]),
        ]);

        $client = makeAuthClient();
        $result = $client->post('companies/getAll');

        expect($result[0]['name'])->toBe('Loaded From Cache');

        Http::assertNotSent(function ($request): bool {
            return str_contains((string) $request->url(), 'grant/');
        });
    });
});
