<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Enums\ValidationErrorCode;

describe('ValidationErrorCode', function (): void {
    it('has correct integer values', function (): void {
        expect(ValidationErrorCode::Required->value)->toBe(1)
            ->and(ValidationErrorCode::InvalidEmail->value)->toBe(3)
            ->and(ValidationErrorCode::InvalidPortugueseNif->value)->toBe(8)
            ->and(ValidationErrorCode::CharacterLimitExceeded->value)->toBe(17);
    });

    it('returns Portuguese labels for all codes', function (): void {
        expect(ValidationErrorCode::Required->label())->toContain('obrigatorio')
            ->and(ValidationErrorCode::InvalidEmail->label())->toContain('email')
            ->and(ValidationErrorCode::InvalidPortugueseNif->label())->toContain('NIF')
            ->and(ValidationErrorCode::InvalidDateFormat->label())->toContain('AAAA-MM-DD')
            ->and(ValidationErrorCode::CustomerIdentificationRequired->label())->toContain('CIVA');
    });

    it('can be created from value', function (): void {
        expect(ValidationErrorCode::from(1))->toBe(ValidationErrorCode::Required)
            ->and(ValidationErrorCode::from(8))->toBe(ValidationErrorCode::InvalidPortugueseNif)
            ->and(ValidationErrorCode::tryFrom(99))->toBeNull();
    });

    it('has 17 cases', function (): void {
        expect(ValidationErrorCode::cases())->toHaveCount(17);
    });
});
