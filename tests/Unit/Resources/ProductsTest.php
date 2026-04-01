<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Product as ProductDto;
use DigitaldevLx\LaravelMoloni\Enums\ProductType;
use DigitaldevLx\LaravelMoloni\Events\ProductCreated;
use DigitaldevLx\LaravelMoloni\Events\ProductUpdated;
use DigitaldevLx\LaravelMoloni\Moloni;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

/** @covers \DigitaldevLx\LaravelMoloni\Resources\Products */
describe('Products resource', function (): void {
    beforeEach(function (): void {
        $this->products = app(Moloni::class)->products();
    });

    it('lists all products', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/products/getAll/*' => Http::response([
                ['product_id' => 1, 'name' => 'Widget', 'reference' => 'WGT-001'],
                ['product_id' => 2, 'name' => 'Gadget', 'reference' => 'GDG-001'],
            ]),
        ]);

        $result = $this->products->getAll(companyId: 1);

        expect($result)->toHaveCount(2)
            ->and($result[0]['name'])->toBe('Widget')
            ->and($result[1]['reference'])->toBe('GDG-001');
    });

    it('gets product by reference', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/products/getByReference/*' => Http::response([
                ['product_id' => 7, 'name' => 'Specific Product', 'reference' => 'REF-007'],
            ]),
        ]);

        $result = $this->products->getByReference(companyId: 1, reference: 'REF-007');

        expect($result[0]['reference'])->toBe('REF-007')
            ->and($result[0]['product_id'])->toBe(7);
    });

    it('creates product from DTO and dispatches ProductCreated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/products/insert/*' => Http::response([
                'product_id' => 55,
                'name' => 'New Widget',
                'reference' => 'NW-001',
            ]),
        ]);

        $dto = new ProductDto(
            name: 'New Widget',
            reference: 'NW-001',
            type: ProductType::Product,
            categoryId: 1,
            unitId: 1,
            price: 19.99,
        );

        $result = $this->products->insert(companyId: 1, data: $dto);

        expect($result['product_id'])->toBe(55);

        Event::assertDispatched(ProductCreated::class, function (ProductCreated $event): bool {
            return $event->data['product_id'] === 55;
        });
    });

    it('creates product from raw array and dispatches ProductCreated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/products/insert/*' => Http::response([
                'product_id' => 56,
                'name' => 'Array Widget',
            ]),
        ]);

        $result = $this->products->insert(companyId: 1, data: [
            'name' => 'Array Widget',
            'reference' => 'AW-001',
            'type' => 1,
            'category_id' => 1,
            'unit_id' => 1,
            'price' => 9.99,
        ]);

        expect($result['product_id'])->toBe(56);
        Event::assertDispatched(ProductCreated::class);
    });

    it('updates product and dispatches ProductUpdated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/products/update/*' => Http::response([
                'product_id' => 20,
                'name' => 'Updated Widget',
                'price' => 24.99,
            ]),
        ]);

        $result = $this->products->update(companyId: 1, productId: 20, data: ['price' => 24.99]);

        expect($result['product_id'])->toBe(20)
            ->and($result['price'])->toBe(24.99);

        Event::assertDispatched(ProductUpdated::class, function (ProductUpdated $event): bool {
            return $event->data['product_id'] === 20;
        });
    });

    it('deletes a product', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/products/delete/*' => Http::response([
                'valid' => 1,
            ]),
        ]);

        $result = $this->products->delete(companyId: 1, productId: 20);

        expect($result['valid'])->toBe(1);
    });
});
