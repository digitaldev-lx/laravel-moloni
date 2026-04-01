<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

/** @covers MoloniEndpoint */
describe('MoloniEndpoint attribute', function (): void {
    it('can be instantiated with a path', function (): void {
        $attribute = new MoloniEndpoint(path: 'customers/getAll');

        expect($attribute->path)->toBe('customers/getAll')
            ->and($attribute->description)->toBe('');
    });

    it('can be instantiated with a path and a description', function (): void {
        $attribute = new MoloniEndpoint(path: 'invoices/insert', description: 'Insert an invoice');

        expect($attribute->path)->toBe('invoices/insert')
            ->and($attribute->description)->toBe('Insert an invoice');
    });

    it('targets methods only via the Attribute flag', function (): void {
        $reflection = new ReflectionClass(MoloniEndpoint::class);
        $attributes = $reflection->getAttributes(Attribute::class);

        expect($attributes)->not->toBeEmpty();

        /** @var Attribute $attr */
        $attr = $attributes[0]->newInstance();

        expect($attr->flags)->toBe(Attribute::TARGET_METHOD);
    });

    it('is a readonly final class', function (): void {
        $reflection = new ReflectionClass(MoloniEndpoint::class);

        expect($reflection->isReadOnly())->toBeTrue()
            ->and($reflection->isFinal())->toBeTrue();
    });
});
