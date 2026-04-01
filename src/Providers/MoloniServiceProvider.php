<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Providers;

use DigitaldevLx\LaravelMoloni\Http\MoloniClient;
use DigitaldevLx\LaravelMoloni\Moloni;
use Illuminate\Support\ServiceProvider;

final class MoloniServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/moloni.php', 'moloni');

        $this->app->singleton(MoloniClient::class, function ($app): MoloniClient {
            /** @var array{client_id: string, client_secret: string, username: string, password: string} $config */
            $config = $app['config']['moloni'];

            return new MoloniClient(
                clientId: $config['client_id'] ?? '',
                clientSecret: $config['client_secret'] ?? '',
                username: $config['username'] ?? '',
                password: $config['password'] ?? '',
            );
        });

        $this->app->singleton(Moloni::class, function ($app): Moloni {
            return new Moloni($app->make(MoloniClient::class));
        });

        $this->app->alias(Moloni::class, 'moloni');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/moloni.php' => config_path('moloni.php'),
            ], 'moloni-config');

            $this->publishes([
                __DIR__ . '/../../database/migrations/' => database_path('migrations'),
            ], 'moloni-migrations');
        }

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
