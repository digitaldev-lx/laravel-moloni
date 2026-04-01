<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Document;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\DocumentProduct;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Payment;

/** @covers Document */
describe('Document DTO', function (): void {
    it('creates from constructor with required fields', function (): void {
        $document = new Document(
            documentSetId: 10,
            customerId: 42,
            date: '2026-01-15',
            expirationDate: '2026-02-15',
        );

        expect($document->documentSetId)->toBe(10)
            ->and($document->customerId)->toBe(42)
            ->and($document->date)->toBe('2026-01-15')
            ->and($document->expirationDate)->toBe('2026-02-15')
            ->and($document->products)->toBe([])
            ->and($document->payments)->toBeNull()
            ->and($document->notes)->toBeNull();
    });

    it('converts to array omitting null values', function (): void {
        $document = new Document(
            documentSetId: 10,
            customerId: 42,
            date: '2026-01-15',
            expirationDate: '2026-02-15',
        );

        $array = $document->toArray();

        expect($array)->toMatchArray([
            'document_set_id' => 10,
            'customer_id' => 42,
            'date' => '2026-01-15',
            'expiration_date' => '2026-02-15',
        ])
            ->and($array)->not->toHaveKey('notes')
            ->and($array)->not->toHaveKey('payments')
            ->and($array)->not->toHaveKey('products'); // empty products array is excluded
    });

    it('converts to array with nested document products', function (): void {
        $product = new DocumentProduct(productId: 5, qty: 2.0, price: 15.00);

        $document = new Document(
            documentSetId: 10,
            customerId: 42,
            date: '2026-01-15',
            expirationDate: '2026-02-15',
            products: [$product],
        );

        $array = $document->toArray();

        expect($array)->toHaveKey('products')
            ->and($array['products'])->toHaveCount(1)
            ->and($array['products'][0])->toMatchArray([
                'product_id' => 5,
                'qty' => 2.0,
                'price' => 15.00,
            ]);
    });

    it('converts to array with nested payments', function (): void {
        $payment = new Payment(paymentMethodId: 3, value: 30.00, date: '2026-01-15');

        $document = new Document(
            documentSetId: 10,
            customerId: 42,
            date: '2026-01-15',
            expirationDate: '2026-02-15',
            payments: [$payment],
        );

        $array = $document->toArray();

        expect($array)->toHaveKey('payments')
            ->and($array['payments'])->toHaveCount(1)
            ->and($array['payments'][0])->toMatchArray([
                'payment_method_id' => 3,
                'value' => 30.00,
                'date' => '2026-01-15',
            ]);
    });

    it('creates from array with nested document products', function (): void {
        $document = Document::fromArray([
            'document_set_id' => '10',
            'customer_id' => '42',
            'date' => '2026-01-15',
            'expiration_date' => '2026-02-15',
            'products' => [
                ['product_id' => '5', 'qty' => '2', 'price' => '15.00'],
            ],
        ]);

        expect($document->documentSetId)->toBe(10)
            ->and($document->customerId)->toBe(42)
            ->and($document->products)->toHaveCount(1)
            ->and($document->products[0])->toBeInstanceOf(DocumentProduct::class)
            ->and($document->products[0]->productId)->toBe(5)
            ->and($document->products[0]->qty)->toBe(2.0);
    });

    it('creates from array with nested payments', function (): void {
        $document = Document::fromArray([
            'document_set_id' => '1',
            'customer_id' => '1',
            'date' => '2026-01-01',
            'expiration_date' => '2026-02-01',
            'payments' => [
                ['payment_method_id' => '3', 'value' => '100.00'],
            ],
        ]);

        expect($document->payments)->toHaveCount(1)
            ->and($document->payments[0])->toBeInstanceOf(Payment::class)
            ->and($document->payments[0]->paymentMethodId)->toBe(3)
            ->and($document->payments[0]->value)->toBe(100.0);
    });

    it('creates from array with absent optional fields set to null', function (): void {
        $document = Document::fromArray([
            'document_set_id' => '1',
            'customer_id' => '1',
            'date' => '2026-01-01',
            'expiration_date' => '2026-02-01',
        ]);

        expect($document->ourReference)->toBeNull()
            ->and($document->yourReference)->toBeNull()
            ->and($document->notes)->toBeNull()
            ->and($document->payments)->toBeNull()
            ->and($document->salespersonId)->toBeNull()
            ->and($document->financialDiscount)->toBeNull();
    });
});
