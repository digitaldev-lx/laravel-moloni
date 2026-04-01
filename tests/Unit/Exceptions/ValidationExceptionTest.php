<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Exceptions\MoloniException;
use DigitaldevLx\LaravelMoloni\Exceptions\ValidationException;

describe('ValidationException', function (): void {
    it('extends MoloniException', function (): void {
        $e = new ValidationException('test');

        expect($e)->toBeInstanceOf(MoloniException::class);
    });

    it('creates from response with validation errors', function (): void {
        $e = ValidationException::fromResponse([
            'vat' => [8],
            'name' => [1],
            'email' => [3],
        ]);

        expect($e->errors)->toHaveCount(3)
            ->and($e->getMessage())->toContain('vat')
            ->and($e->getMessage())->toContain('name')
            ->and($e->getMessage())->toContain('email');
    });

    it('returns field errors as associative array', function (): void {
        $e = ValidationException::fromResponse([
            'vat' => [8],
            'email' => [3],
        ]);

        $fieldErrors = $e->getFieldErrors();

        expect($fieldErrors)->toHaveKey('vat')
            ->and($fieldErrors)->toHaveKey('email')
            ->and($fieldErrors['vat'])->toContain('NIF')
            ->and($fieldErrors['email'])->toContain('email');
    });

    it('checks if specific field has error', function (): void {
        $e = ValidationException::fromResponse([
            'vat' => [8],
        ]);

        expect($e->hasFieldError('vat'))->toBeTrue()
            ->and($e->hasFieldError('name'))->toBeFalse();
    });

    it('handles empty response', function (): void {
        $e = ValidationException::fromResponse([]);

        expect($e->errors)->toBeEmpty()
            ->and($e->getMessage())->toBe('Moloni validation error');
    });

    it('ignores non-validation fields in response', function (): void {
        $e = ValidationException::fromResponse([
            'vat' => [8],
            'some_string' => 'not an error',
            'nested' => ['not' => 'validation'],
        ]);

        expect($e->errors)->toHaveCount(1)
            ->and($e->hasFieldError('vat'))->toBeTrue();
    });
});
