<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Enums;

enum DocumentType: string
{
    case Invoice = 'invoices';
    case Receipt = 'receipts';
    case CreditNote = 'creditNotes';
    case DebitNote = 'debitNotes';
    case SimplifiedInvoice = 'simplifiedInvoices';
    case InvoiceReceipt = 'invoiceReceipts';
    case DeliveryNote = 'deliveryNotes';
    case BillOfLading = 'billsOfLading';
    case Waybill = 'waybills';
    case Estimate = 'estimates';
    case InternalDocument = 'internalDocuments';
    case CustomerReturnNote = 'customerReturnNotes';
    case PurchaseOrder = 'purchaseOrder';

    public function label(): string
    {
        return match ($this) {
            self::Invoice => 'Fatura',
            self::Receipt => 'Recibo',
            self::CreditNote => 'Nota de Crédito',
            self::DebitNote => 'Nota de Débito',
            self::SimplifiedInvoice => 'Fatura Simplificada',
            self::InvoiceReceipt => 'Fatura-Recibo',
            self::DeliveryNote => 'Guia de Remessa',
            self::BillOfLading => 'Guia de Transporte',
            self::Waybill => 'Guia de Consignação',
            self::Estimate => 'Orçamento',
            self::InternalDocument => 'Documento Interno',
            self::CustomerReturnNote => 'Nota de Devolução',
            self::PurchaseOrder => 'Nota de Encomenda',
        };
    }
}
