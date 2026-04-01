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
        $this->mergeConfigFrom(__DIR__.'/../../config/moloni.php', 'moloni');

        $this->app->singleton(MoloniClient::class, function ($app): MoloniClient {
            $config = $app['config']['moloni'];

            return new MoloniClient(
                clientId: (string) ($config['client_id'] ?? ''),
                clientSecret: (string) ($config['client_secret'] ?? ''),
                username: (string) ($config['username'] ?? ''),
                password: (string) ($config['password'] ?? ''),
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
                __DIR__.'/../../config/moloni.php' => config_path('moloni.php'),
            ], 'moloni-config');

            $this->publishes([
                __DIR__.'/../../database/migrations/' => database_path('migrations'),
            ], 'moloni-migrations');
        }

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
