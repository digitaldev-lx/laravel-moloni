<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Customer as CustomerDto;
use DigitaldevLx\LaravelMoloni\Events\CustomerCreated;
use DigitaldevLx\LaravelMoloni\Events\CustomerUpdated;
use DigitaldevLx\LaravelMoloni\Moloni;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

/** @covers \DigitaldevLx\LaravelMoloni\Resources\Customers */
describe('Customers resource', function (): void {
    beforeEach(function (): void {
        $this->customers = app(Moloni::class)->customers();
    });

    it('lists all customers', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/getAll/*' => Http::response([
                ['customer_id' => 1, 'name' => 'Customer A'],
                ['customer_id' => 2, 'name' => 'Customer B'],
            ]),
        ]);

        $result = $this->customers->getAll(companyId: 1);

        expect($result)->toHaveCount(2)
            ->and($result[0]['name'])->toBe('Customer A')
            ->and($result[1]['name'])->toBe('Customer B');
    });

    it('gets customer by id', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/getOne/*' => Http::response([
                'customer_id' => 99,
                'name' => 'Specific Customer',
                'vat' => '123456789',
            ]),
        ]);

        $result = $this->customers->getOne(companyId: 1, customerId: 99);

        expect($result['customer_id'])->toBe(99)
            ->and($result['name'])->toBe('Specific Customer');
    });

    it('searches customers by VAT', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/getByVat/*' => Http::response([
                ['customer_id' => 5, 'vat' => '999999990', 'name' => 'VAT Customer'],
            ]),
        ]);

        $result = $this->customers->getByVat(companyId: 1, vat: '999999990');

        expect($result[0]['vat'])->toBe('999999990');
    });

    it('creates customer with raw array and dispatches CustomerCreated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/insert/*' => Http::response([
                'customer_id' => 101,
                'name' => 'New Customer',
            ]),
        ]);

        $result = $this->customers->insert(companyId: 1, data: [
            'vat' => '111111111',
            'number' => 'C100',
            'name' => 'New Customer',
        ]);

        expect($result['customer_id'])->toBe(101);

        Event::assertDispatched(CustomerCreated::class, function (CustomerCreated $event): bool {
            return $event->data['customer_id'] === 101;
        });
    });

    it('creates customer from DTO and dispatches CustomerCreated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/insert/*' => Http::response([
                'customer_id' => 102,
                'name' => 'DTO Customer',
            ]),
        ]);

        $dto = new CustomerDto(
            vat: '222222222',
            number: 'C200',
            name: 'DTO Customer',
            email: 'dto@example.pt',
        );

        $result = $this->customers->insert(companyId: 1, data: $dto);

        expect($result['customer_id'])->toBe(102);
        Event::assertDispatched(CustomerCreated::class);
    });

    it('updates customer and dispatches CustomerUpdated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/update/*' => Http::response([
                'customer_id' => 50,
                'name' => 'Updated Customer',
            ]),
        ]);

        $result = $this->customers->update(companyId: 1, customerId: 50, data: [
            'name' => 'Updated Customer',
        ]);

        expect($result['customer_id'])->toBe(50);

        Event::assertDispatched(CustomerUpdated::class, function (CustomerUpdated $event): bool {
            return $event->data['customer_id'] === 50;
        });
    });

    it('deletes a customer', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/delete/*' => Http::response([
                'valid' => 1,
            ]),
        ]);

        $result = $this->customers->delete(companyId: 1, customerId: 99);

        expect($result['valid'])->toBe(1);
    });

    it('counts customers', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/customers/count/*' => Http::response([
                'count' => 42,
            ]),
        ]);

        $result = $this->customers->count(companyId: 1);

        expect($result['count'])->toBe(42);
    });
});
