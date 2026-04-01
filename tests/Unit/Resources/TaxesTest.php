<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Moloni;
use Illuminate\Support\Facades\Http;

/** @covers \DigitaldevLx\LaravelMoloni\Resources\Taxes */
describe('Taxes resource', function (): void {
    beforeEach(function (): void {
        $this->taxes = app(Moloni::class)->taxes();
    });

    it('lists all taxes', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/taxes/getAll/*' => Http::response([
                ['tax_id' => 1, 'name' => 'IVA 23%', 'value' => 23],
                ['tax_id' => 2, 'name' => 'IVA 6%', 'value' => 6],
            ]),
        ]);

        $result = $this->taxes->getAll(companyId: 1);

        expect($result)->toHaveCount(2)
            ->and($result[0]['name'])->toBe('IVA 23%')
            ->and($result[1]['value'])->toBe(6);
    });

    it('creates a tax', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/taxes/insert/*' => Http::response([
                'tax_id' => 10,
                'name' => 'IVA 13%',
                'value' => 13,
            ]),
        ]);

        $result = $this->taxes->insert(companyId: 1, data: [
            'name' => 'IVA 13%',
            'value' => 13,
            'type' => 1,
        ]);

        expect($result['tax_id'])->toBe(10)
            ->and($result['name'])->toBe('IVA 13%');
    });

    it('updates a tax', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/taxes/update/*' => Http::response([
                'tax_id' => 1,
                'name' => 'IVA 23% Updated',
                'value' => 23,
            ]),
        ]);

        $result = $this->taxes->update(companyId: 1, data: [
            'tax_id' => 1,
            'name' => 'IVA 23% Updated',
        ]);

        expect($result['tax_id'])->toBe(1)
            ->and($result['name'])->toBe('IVA 23% Updated');
    });

    it('deletes a tax', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/taxes/delete/*' => Http::response([
                'valid' => 1,
            ]),
        ]);

        $result = $this->taxes->delete(companyId: 1, taxId: 1);

        expect($result['valid'])->toBe(1);
    });
});
