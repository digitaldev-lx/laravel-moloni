<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Moloni;
use Illuminate\Support\Facades\Http;

/** @covers \DigitaldevLx\LaravelMoloni\Resources\DocumentSets */
describe('DocumentSets resource', function (): void {
    beforeEach(function (): void {
        $this->documentSets = app(Moloni::class)->documentSets();
    });

    it('lists all document sets', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/documentSets/getAll/*' => Http::response([
                ['document_set_id' => 1, 'name' => 'Série A 2026'],
                ['document_set_id' => 2, 'name' => 'Série B 2026'],
            ]),
        ]);

        $result = $this->documentSets->getAll(companyId: 1);

        expect($result)->toHaveCount(2)
            ->and($result[0]['name'])->toBe('Série A 2026')
            ->and($result[1]['document_set_id'])->toBe(2);
    });

    it('creates a document set', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/documentSets/insert/*' => Http::response([
                'document_set_id' => 10,
                'name' => 'Série C 2026',
            ]),
        ]);

        $result = $this->documentSets->insert(companyId: 1, data: [
            'name' => 'Série C 2026',
        ]);

        expect($result['document_set_id'])->toBe(10)
            ->and($result['name'])->toBe('Série C 2026');
    });

    it('deletes a document set', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/documentSets/delete/*' => Http::response([
                'valid' => 1,
            ]),
        ]);

        $result = $this->documentSets->delete(companyId: 1, documentSetId: 10);

        expect($result['valid'])->toBe(1);
    });
});
