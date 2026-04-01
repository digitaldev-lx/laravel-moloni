<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Document as DocumentDto;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\DocumentProduct;
use DigitaldevLx\LaravelMoloni\Events\DocumentCreated;
use DigitaldevLx\LaravelMoloni\Moloni;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

/** @covers \DigitaldevLx\LaravelMoloni\Resources\Documents\Invoices */
describe('Invoices resource', function (): void {
    beforeEach(function (): void {
        $this->invoices = app(Moloni::class)->invoices();
    });

    it('lists all invoices', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/getAll/*' => Http::response([
                ['document_id' => 1, 'customer_id' => 10],
                ['document_id' => 2, 'customer_id' => 20],
            ]),
        ]);

        $result = $this->invoices->getAll(companyId: 1);

        expect($result)->toHaveCount(2)
            ->and($result[0]['document_id'])->toBe(1)
            ->and($result[1]['document_id'])->toBe(2);
    });

    it('gets a single invoice by id', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/getOne/*' => Http::response([
                'document_id' => 77,
                'customer_id' => 42,
                'date' => '2026-01-10',
            ]),
        ]);

        $result = $this->invoices->getOne(companyId: 1, documentId: 77);

        expect($result['document_id'])->toBe(77)
            ->and($result['customer_id'])->toBe(42);
    });

    it('creates invoice from raw array and dispatches DocumentCreated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/insert/*' => Http::response([
                'document_id' => 300,
                'customer_id' => 42,
            ]),
        ]);

        $result = $this->invoices->insert(companyId: 1, data: [
            'document_set_id' => 10,
            'customer_id' => 42,
            'date' => '2026-01-15',
            'expiration_date' => '2026-02-15',
        ]);

        expect($result['document_id'])->toBe(300);

        Event::assertDispatched(DocumentCreated::class, function (DocumentCreated $event): bool {
            return $event->data['document_id'] === 300
                && $event->documentType === 'invoices';
        });
    });

    it('creates invoice from DTO and dispatches DocumentCreated event', function (): void {
        Event::fake();

        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/insert/*' => Http::response([
                'document_id' => 301,
                'customer_id' => 55,
            ]),
        ]);

        $dto = new DocumentDto(
            documentSetId: 10,
            customerId: 55,
            date: '2026-01-15',
            expirationDate: '2026-02-15',
            products: [
                new DocumentProduct(productId: 1, qty: 1.0, price: 50.00),
            ],
        );

        $result = $this->invoices->insert(companyId: 1, data: $dto);

        expect($result['document_id'])->toBe(301);
        Event::assertDispatched(DocumentCreated::class);
    });

    it('updates an invoice', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/update/*' => Http::response([
                'document_id' => 77,
                'notes' => 'Updated notes',
            ]),
        ]);

        $result = $this->invoices->update(companyId: 1, documentId: 77, data: ['notes' => 'Updated notes']);

        expect($result['document_id'])->toBe(77)
            ->and($result['notes'])->toBe('Updated notes');
    });

    it('deletes an invoice', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/delete/*' => Http::response([
                'valid' => 1,
            ]),
        ]);

        $result = $this->invoices->delete(companyId: 1, documentId: 77);

        expect($result['valid'])->toBe(1);
    });

    it('gets PDF link for an invoice', function (): void {
        Http::fake([
            'api.moloni.pt/v1/grant/' => Http::response([
                'access_token' => 'test-token',
                'refresh_token' => 'test-refresh',
                'expires_in' => 3600,
            ]),
            'api.moloni.pt/v1/invoices/getPDFLink/*' => Http::response([
                'url' => 'https://app.moloni.pt/invoice/77.pdf',
            ]),
        ]);

        $result = $this->invoices->getPdfLink(companyId: 1, documentId: 77);

        expect($result['url'])->toBe('https://app.moloni.pt/invoice/77.pdf');
    });
});
