<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Enums\DocumentType;

/** @covers DocumentType */
describe('DocumentType enum', function (): void {
    it('has the correct string values', function (): void {
        expect(DocumentType::Invoice->value)->toBe('invoices')
            ->and(DocumentType::Receipt->value)->toBe('receipts')
            ->and(DocumentType::CreditNote->value)->toBe('creditNotes')
            ->and(DocumentType::DebitNote->value)->toBe('debitNotes')
            ->and(DocumentType::SimplifiedInvoice->value)->toBe('simplifiedInvoices')
            ->and(DocumentType::InvoiceReceipt->value)->toBe('invoiceReceipts')
            ->and(DocumentType::DeliveryNote->value)->toBe('deliveryNotes')
            ->and(DocumentType::BillOfLading->value)->toBe('billsOfLading')
            ->and(DocumentType::Waybill->value)->toBe('waybills')
            ->and(DocumentType::Estimate->value)->toBe('estimates')
            ->and(DocumentType::InternalDocument->value)->toBe('internalDocuments')
            ->and(DocumentType::CustomerReturnNote->value)->toBe('customerReturnNotes')
            ->and(DocumentType::PurchaseOrder->value)->toBe('purchaseOrder');
    });

    it('returns Portuguese labels', function (): void {
        expect(DocumentType::Invoice->label())->toBe('Fatura')
            ->and(DocumentType::Receipt->label())->toBe('Recibo')
            ->and(DocumentType::CreditNote->label())->toBe('Nota de Crédito')
            ->and(DocumentType::DebitNote->label())->toBe('Nota de Débito')
            ->and(DocumentType::SimplifiedInvoice->label())->toBe('Fatura Simplificada')
            ->and(DocumentType::InvoiceReceipt->label())->toBe('Fatura-Recibo')
            ->and(DocumentType::DeliveryNote->label())->toBe('Guia de Remessa')
            ->and(DocumentType::BillOfLading->label())->toBe('Guia de Transporte')
            ->and(DocumentType::Waybill->label())->toBe('Guia de Consignação')
            ->and(DocumentType::Estimate->label())->toBe('Orçamento')
            ->and(DocumentType::InternalDocument->label())->toBe('Documento Interno')
            ->and(DocumentType::CustomerReturnNote->label())->toBe('Nota de Devolução')
            ->and(DocumentType::PurchaseOrder->label())->toBe('Nota de Encomenda');
    });

    it('can be created from a string value', function (): void {
        expect(DocumentType::from('invoices'))->toBe(DocumentType::Invoice)
            ->and(DocumentType::from('receipts'))->toBe(DocumentType::Receipt)
            ->and(DocumentType::from('creditNotes'))->toBe(DocumentType::CreditNote);
    });

    it('returns null when trying to create from an invalid value', function (): void {
        expect(DocumentType::tryFrom('invalid'))->toBeNull()
            ->and(DocumentType::tryFrom(''))->toBeNull();
    });

    it('contains all expected cases', function (): void {
        $cases = DocumentType::cases();

        expect($cases)->toHaveCount(13);
    });
});
