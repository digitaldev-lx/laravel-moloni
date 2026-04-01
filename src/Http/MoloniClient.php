<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Http;

use DigitaldevLx\LaravelMoloni\Events\TokenRefreshed;
use DigitaldevLx\LaravelMoloni\Exceptions\AuthenticationException;
use DigitaldevLx\LaravelMoloni\Exceptions\MoloniException;
use DigitaldevLx\LaravelMoloni\Exceptions\RateLimitException;
use DigitaldevLx\LaravelMoloni\Exceptions\ValidationException;
use DigitaldevLx\LaravelMoloni\Models\MoloniToken;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class MoloniClient
{
    private const string BASE_URL = 'https://api.moloni.pt/v1/';

    private const string AUTH_URL = 'https://api.moloni.pt/v1/grant/';

    private ?string $accessToken = null;

    private ?string $refreshToken = null;

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $username,
        private readonly string $password,
    ) {
        $this->loadTokensFromStorage();
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function post(string $endpoint, array $params = []): array
    {
        $this->ensureAuthenticated();

        $response = $this->httpClient()
            ->post(self::BASE_URL.$endpoint.'/', array_merge(['json' => true, 'human_errors' => true], $params));

        if ($response->status() === 401) {
            $this->refreshAccessToken();

            $response = $this->httpClient()
                ->post(self::BASE_URL.$endpoint.'/', array_merge(['json' => true, 'human_errors' => true], $params));
        }

        if ($response->status() === 429) {
            throw new RateLimitException('Moloni API rate limit exceeded.');
        }

        if ($response->failed()) {
            $this->handleErrorResponse($response, $endpoint);
        }

        /** @var array<string, mixed> $json */
        $json = $response->json() ?? [];

        $this->detectApiErrors($json, $endpoint);

        return $json;
    }

    public function authenticate(): void
    {
        $response = Http::asForm()->post(self::AUTH_URL, [
            'grant_type' => 'password',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->username,
            'password' => $this->password,
        ]);

        if ($response->failed()) {
            /** @var array{error?: string, error_description?: string} $data */
            $data = $response->json() ?? [];

            if (isset($data['error'])) {
                throw AuthenticationException::fromResponse($data);
            }

            throw new AuthenticationException(
                'Failed to authenticate with Moloni API: '.$response->body(),
                code: $response->status(),
            );
        }

        /** @var array{access_token: string, refresh_token: string, expires_in: int} $data */
        $data = $response->json();
        $this->accessToken = $data['access_token'];
        $this->refreshToken = $data['refresh_token'];
        $this->persistTokens($data);
    }

    public function refreshAccessToken(): void
    {
        if ($this->refreshToken === null) {
            $this->authenticate();

            return;
        }

        $response = Http::asForm()->post(self::AUTH_URL, [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken,
        ]);

        if ($response->failed()) {
            $this->authenticate();

            return;
        }

        /** @var array{access_token: string, refresh_token: string, expires_in: int} $data */
        $data = $response->json();
        $this->accessToken = $data['access_token'];
        $this->refreshToken = $data['refresh_token'];
        $this->persistTokens($data);

        event(new TokenRefreshed);
    }

    private function httpClient(): PendingRequest
    {
        return Http::asForm()
            ->withQueryParameters(['access_token' => $this->accessToken]);
    }

    private function ensureAuthenticated(): void
    {
        if ($this->accessToken !== null) {
            return;
        }

        $this->authenticate();
    }

    private function loadTokensFromStorage(): void
    {
        $token = MoloniToken::current();

        if ($token !== null && ! $token->isExpired()) {
            $this->accessToken = $token->access_token;
            $this->refreshToken = $token->refresh_token;
        }
    }

    /**
     * @param  array{access_token: string, refresh_token: string, expires_in: int}  $data
     */
    private function persistTokens(array $data): void
    {
        MoloniToken::query()->create([
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'expires_at' => now()->addSeconds($data['expires_in']),
        ]);
    }

    private function handleErrorResponse(Response $response, string $endpoint): never
    {
        /** @var array<string, mixed> $body */
        $body = $response->json() ?? [];

        if (isset($body['error'])) {
            throw AuthenticationException::fromResponse($body);
        }

        throw new MoloniException(
            message: "Moloni API error on {$endpoint} (HTTP {$response->status()}): ".$response->body(),
            code: $response->status(),
        );
    }

    /**
     * @param  array<string, mixed>  $json
     */
    private function detectApiErrors(array $json, string $endpoint): void
    {
        if ($this->isValidationError($json)) {
            throw ValidationException::fromResponse($json);
        }
    }

    /**
     * @param  array<string, mixed>  $json
     */
    private function isValidationError(array $json): bool
    {
        foreach ($json as $value) {
            if (is_array($value) && isset($value[0]) && is_int($value[0]) && $value[0] >= 1 && $value[0] <= 17) {
                return true;
            }
        }

        return false;
    }
}
