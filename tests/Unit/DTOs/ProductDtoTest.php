<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Product;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Tax;
use DigitaldevLx\LaravelMoloni\Enums\ProductType;

/** @covers Product */
describe('Product DTO', function (): void {
    it('creates from constructor with required fields and a ProductType enum', function (): void {
        $product = new Product(
            name: 'Widget Pro',
            reference: 'WGT-001',
            type: ProductType::Product,
            categoryId: 10,
            unitId: 1,
            price: 29.99,
        );

        expect($product->name)->toBe('Widget Pro')
            ->and($product->reference)->toBe('WGT-001')
            ->and($product->type)->toBe(ProductType::Product)
            ->and($product->categoryId)->toBe(10)
            ->and($product->unitId)->toBe(1)
            ->and($product->price)->toBe(29.99)
            ->and($product->taxes)->toBeNull();
    });

    it('converts to array with type as its integer value', function (): void {
        $product = new Product(
            name: 'Consulting',
            reference: 'SRV-001',
            type: ProductType::Service,
            categoryId: 5,
            unitId: 2,
            price: 100.00,
        );

        $array = $product->toArray();

        expect($array['type'])->toBe(2)
            ->and($array['name'])->toBe('Consulting')
            ->and($array['reference'])->toBe('SRV-001')
            ->and($array['category_id'])->toBe(5)
            ->and($array['unit_id'])->toBe(2)
            ->and($array['price'])->toBe(100.00);
    });

    it('omits null fields from toArray', function (): void {
        $product = new Product(
            name: 'Widget',
            reference: 'WGT-002',
            type: ProductType::Other,
            categoryId: 1,
            unitId: 1,
            price: 9.99,
        );

        $array = $product->toArray();

        expect($array)->not->toHaveKey('ean')
            ->and($array)->not->toHaveKey('summary')
            ->and($array)->not->toHaveKey('warehouse_id')
            ->and($array)->not->toHaveKey('taxes');
    });

    it('includes taxes in toArray when present', function (): void {
        $tax = new Tax(taxId: 1, value: 23.0, order: 1, cumulative: 0);

        $product = new Product(
            name: 'Taxed Widget',
            reference: 'WGT-003',
            type: ProductType::Product,
            categoryId: 1,
            unitId: 1,
            price: 50.00,
            taxes: [$tax],
        );

        $array = $product->toArray();

        expect($array)->toHaveKey('taxes')
            ->and($array['taxes'])->toHaveCount(1)
            ->and($array['taxes'][0])->toMatchArray(['tax_id' => 1, 'value' => 23.0]);
    });

    it('creates from array', function (): void {
        $product = Product::fromArray([
            'name' => 'Gadget',
            'reference' => 'GDG-001',
            'type' => '2',
            'category_id' => '7',
            'unit_id' => '3',
            'price' => '49.99',
        ]);

        expect($product->name)->toBe('Gadget')
            ->and($product->type)->toBe(ProductType::Service)
            ->and($product->categoryId)->toBe(7)
            ->and($product->unitId)->toBe(3)
            ->and($product->price)->toBe(49.99)
            ->and($product->taxes)->toBeNull();
    });

    it('creates from array with nested taxes', function (): void {
        $product = Product::fromArray([
            'name' => 'Taxed Gadget',
            'reference' => 'GDG-002',
            'type' => '1',
            'category_id' => '1',
            'unit_id' => '1',
            'price' => '10.00',
            'taxes' => [
                ['tax_id' => 2, 'value' => 6.0],
            ],
        ]);

        expect($product->taxes)->toHaveCount(1)
            ->and($product->taxes[0])->toBeInstanceOf(Tax::class)
            ->and($product->taxes[0]->taxId)->toBe(2)
            ->and($product->taxes[0]->value)->toBe(6.0);
    });
});
