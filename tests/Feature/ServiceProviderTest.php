<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Http\MoloniClient;
use DigitaldevLx\LaravelMoloni\Moloni;
use DigitaldevLx\LaravelMoloni\Providers\MoloniServiceProvider;
use Illuminate\Support\ServiceProvider;

/** @covers MoloniServiceProvider */
describe('MoloniServiceProvider', function (): void {
    it('registers MoloniClient as a singleton', function (): void {
        $first = app(MoloniClient::class);
        $second = app(MoloniClient::class);

        expect($first)->toBeInstanceOf(MoloniClient::class)
            ->and($first)->toBe($second);
    });

    it('registers Moloni manager as a singleton', function (): void {
        $first = app(Moloni::class);
        $second = app(Moloni::class);

        expect($first)->toBeInstanceOf(Moloni::class)
            ->and($first)->toBe($second);
    });

    it('resolves Moloni manager via the moloni alias', function (): void {
        $manager = app('moloni');

        expect($manager)->toBeInstanceOf(Moloni::class);
    });

    it('merges the moloni config', function (): void {
        expect(config('moloni.client_id'))->toBe('test-client-id')
            ->and(config('moloni.client_secret'))->toBe('test-client-secret')
            ->and(config('moloni.username'))->toBe('test@example.com')
            ->and(config('moloni.password'))->toBe('test-password')
            ->and(config('moloni.company_id'))->toBe(1);
    });

    it('loads migrations and creates the moloni_tokens table', function (): void {
        // The table was created by the migration, so we can run a query against it
        expect(\DigitaldevLx\LaravelMoloni\Models\MoloniToken::query()->count())->toBe(0);
    });

    it('builds MoloniClient using config values', function (): void {
        $client = app(MoloniClient::class);

        expect($client)->toBeInstanceOf(MoloniClient::class);
    });

    it('publishes config with the moloni-config tag', function (): void {
        $paths = ServiceProvider::pathsToPublish(MoloniServiceProvider::class, 'moloni-config');

        expect($paths)->not->toBeEmpty();

        $sourcePath = array_key_first($paths);

        expect(file_exists($sourcePath))->toBeTrue()
            ->and(str_ends_with($sourcePath, 'config/moloni.php'))->toBeTrue();
    });

    it('publishes migrations with the moloni-migrations tag', function (): void {
        $paths = ServiceProvider::pathsToPublish(MoloniServiceProvider::class, 'moloni-migrations');

        expect($paths)->not->toBeEmpty();

        $sourcePath = array_key_first($paths);

        expect(is_dir($sourcePath))->toBeTrue();
    });
});
