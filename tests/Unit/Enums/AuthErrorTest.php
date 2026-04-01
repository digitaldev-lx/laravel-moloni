<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Enums\AuthError;

describe('AuthError', function (): void {
    it('has correct string values', function (): void {
        expect(AuthError::InvalidClient->value)->toBe('invalid_client')
            ->and(AuthError::InvalidGrant->value)->toBe('invalid_grant')
            ->and(AuthError::UnsupportedGrantType->value)->toBe('unsupported_grant_type')
            ->and(AuthError::InvalidScope->value)->toBe('invalid_scope');
    });

    it('returns Portuguese labels', function (): void {
        expect(AuthError::InvalidClient->label())->toContain('Client ID')
            ->and(AuthError::InvalidGrant->label())->toContain('credenciais')
            ->and(AuthError::InvalidScope->label())->toContain('Scope');
    });

    it('can be created from value', function (): void {
        expect(AuthError::from('invalid_client'))->toBe(AuthError::InvalidClient)
            ->and(AuthError::tryFrom('nonexistent'))->toBeNull();
    });

    it('has 8 cases', function (): void {
        expect(AuthError::cases())->toHaveCount(8);
    });
});
