<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Moloni;
use Illuminate\Support\Facades\Http;

/** @covers \DigitaldevLx\LaravelMoloni\Resources\Companies */
describe('Companies resource', function (): void {
    beforeEach(function (): void {
        $this->companies = app(Moloni::class)->companies();
    });

    it('lists all companies', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/companies/getAll/*' => Http::response([
                ['company_id' => 1, 'name' => 'Acme Lda'],
                ['company_id' => 2, 'name' => 'Beta SA'],
            ]),
        ]);

        $result = $this->companies->getAll();

        expect($result)->toHaveCount(2)
            ->and($result[0]['name'])->toBe('Acme Lda')
            ->and($result[1]['company_id'])->toBe(2);
    });

    it('gets a single company by id', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/companies/getOne/*' => Http::response([
                'company_id' => 1,
                'name' => 'Acme Lda',
                'vat' => '500000000',
            ]),
        ]);

        $result = $this->companies->getOne(companyId: 1);

        expect($result['company_id'])->toBe(1)
            ->and($result['name'])->toBe('Acme Lda')
            ->and($result['vat'])->toBe('500000000');
    });
});
