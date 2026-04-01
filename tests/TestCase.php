<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Tests;

use DigitaldevLx\LaravelMoloni\Providers\MoloniServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            MoloniServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('moloni.client_id', 'test-client-id');
        $app['config']->set('moloni.client_secret', 'test-client-secret');
        $app['config']->set('moloni.username', 'test@example.com');
        $app['config']->set('moloni.password', 'test-password');
        $app['config']->set('moloni.company_id', 1);
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
