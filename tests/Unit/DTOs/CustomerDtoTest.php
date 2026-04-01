<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Customer;

/** @covers Customer */
describe('Customer DTO', function (): void {
    it('creates from constructor with required fields', function (): void {
        $customer = new Customer(
            vat: '123456789',
            number: 'C001',
            name: 'Acme Lda',
        );

        expect($customer->vat)->toBe('123456789')
            ->and($customer->number)->toBe('C001')
            ->and($customer->name)->toBe('Acme Lda')
            ->and($customer->email)->toBeNull()
            ->and($customer->phone)->toBeNull();
    });

    it('creates from constructor with all fields', function (): void {
        $customer = new Customer(
            vat: '123456789',
            number: 'C001',
            name: 'Acme Lda',
            email: 'billing@acme.pt',
            phone: '910000000',
            address: 'Rua das Flores, 1',
            city: 'Lisboa',
            zipCode: '1000-001',
            countryId: 1,
            contactName: 'João Silva',
            contactEmail: 'joao@acme.pt',
            contactPhone: '910000001',
            maturityDateId: 2,
            paymentMethodId: 3,
            deliveryMethodId: 4,
            notes: 'VIP customer',
            salespersonId: 5,
            discount: 10.5,
            languageId: 6,
        );

        expect($customer->email)->toBe('billing@acme.pt')
            ->and($customer->countryId)->toBe(1)
            ->and($customer->discount)->toBe(10.5);
    });

    it('converts to array with only non-null values', function (): void {
        $customer = new Customer(
            vat: '123456789',
            number: 'C001',
            name: 'Acme Lda',
        );

        $array = $customer->toArray();

        expect($array)->toHaveKeys(['vat', 'number', 'name'])
            ->and($array)->not->toHaveKey('email')
            ->and($array)->not->toHaveKey('phone')
            ->and($array)->not->toHaveKey('country_id');
    });

    it('converts to array mapping camelCase properties to snake_case keys', function (): void {
        $customer = new Customer(
            vat: '123456789',
            number: 'C001',
            name: 'Acme Lda',
            zipCode: '1000-001',
            countryId: 1,
            contactName: 'João',
            paymentMethodId: 3,
        );

        $array = $customer->toArray();

        expect($array)->toMatchArray([
            'vat' => '123456789',
            'number' => 'C001',
            'name' => 'Acme Lda',
            'zip_code' => '1000-001',
            'country_id' => 1,
            'contact_name' => 'João',
            'payment_method_id' => 3,
        ]);
    });

    it('creates from array', function (): void {
        $customer = Customer::fromArray([
            'vat' => '987654321',
            'number' => 'C002',
            'name' => 'Beta SA',
            'email' => 'info@beta.pt',
            'zip_code' => '4000-001',
            'country_id' => 2,
            'maturity_date_id' => 10,
            'discount' => '15.00',
        ]);

        expect($customer->vat)->toBe('987654321')
            ->and($customer->name)->toBe('Beta SA')
            ->and($customer->email)->toBe('info@beta.pt')
            ->and($customer->zipCode)->toBe('4000-001')
            ->and($customer->countryId)->toBe(2)
            ->and($customer->maturityDateId)->toBe(10)
            ->and($customer->discount)->toBe(15.0);
    });

    it('creates from array with optional fields absent', function (): void {
        $customer = Customer::fromArray([
            'vat' => '111111111',
            'number' => 'C003',
            'name' => 'Gamma Lda',
        ]);

        expect($customer->email)->toBeNull()
            ->and($customer->countryId)->toBeNull()
            ->and($customer->discount)->toBeNull();
    });

    it('round-trips through toArray and fromArray', function (): void {
        $original = new Customer(
            vat: '999999990',
            number: 'C099',
            name: 'Round Trip Lda',
            email: 'rt@example.pt',
            zipCode: '2000-001',
            countryId: 3,
            discount: 5.0,
        );

        $rebuilt = Customer::fromArray($original->toArray());

        expect($rebuilt->vat)->toBe($original->vat)
            ->and($rebuilt->number)->toBe($original->number)
            ->and($rebuilt->name)->toBe($original->name)
            ->and($rebuilt->email)->toBe($original->email)
            ->and($rebuilt->zipCode)->toBe($original->zipCode)
            ->and($rebuilt->countryId)->toBe($original->countryId)
            ->and($rebuilt->discount)->toBe($original->discount);
    });
});
