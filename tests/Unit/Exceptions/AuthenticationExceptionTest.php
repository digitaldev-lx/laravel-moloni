<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Enums\AuthError;
use DigitaldevLx\LaravelMoloni\Exceptions\AuthenticationException;
use DigitaldevLx\LaravelMoloni\Exceptions\MoloniException;

describe('AuthenticationException', function (): void {
    it('extends MoloniException', function (): void {
        $e = new AuthenticationException('test');

        expect($e)->toBeInstanceOf(MoloniException::class);
    });

    it('creates from response with known error', function (): void {
        $e = AuthenticationException::fromResponse([
            'error' => 'invalid_client',
            'error_description' => 'The client id is not valid',
        ]);

        expect($e->authError)->toBe(AuthError::InvalidClient)
            ->and($e->errorDescription)->toBe('The client id is not valid')
            ->and($e->getMessage())->toContain('invalid_client')
            ->and($e->getMessage())->toContain('The client id is not valid')
            ->and($e->getCode())->toBe(400);
    });

    it('creates from response with unknown error', function (): void {
        $e = AuthenticationException::fromResponse([
            'error' => 'some_new_error',
        ]);

        expect($e->authError)->toBeNull()
            ->and($e->errorDescription)->toBeNull()
            ->and($e->getMessage())->toContain('some_new_error');
    });

    it('creates from response with all known error types', function (): void {
        foreach (AuthError::cases() as $error) {
            $e = AuthenticationException::fromResponse(['error' => $error->value]);
            expect($e->authError)->toBe($error);
        }
    });
});
