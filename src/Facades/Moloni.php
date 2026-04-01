<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \DigitaldevLx\LaravelMoloni\Http\MoloniClient client()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Companies companies()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Customers customers()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Suppliers suppliers()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Products products()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\ProductCategories productCategories()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\Invoices invoices()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\Receipts receipts()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\CreditNotes creditNotes()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\DebitNotes debitNotes()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\SimplifiedInvoices simplifiedInvoices()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\InvoiceReceipts invoiceReceipts()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\DeliveryNotes deliveryNotes()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\BillsOfLading billsOfLading()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\Waybills waybills()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\Estimates estimates()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Documents\Documents documents()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Taxes taxes()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\PaymentMethods paymentMethods()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\DocumentSets documentSets()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Warehouses warehouses()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\MeasurementUnits measurementUnits()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\MaturityDates maturityDates()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\DeliveryMethods deliveryMethods()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\BankAccounts bankAccounts()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Countries countries()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\FiscalZones fiscalZones()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Languages languages()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\Currencies currencies()
 * @method static \DigitaldevLx\LaravelMoloni\Resources\TaxExemptions taxExemptions()
 *
 * @see \DigitaldevLx\LaravelMoloni\Moloni
 */
final class Moloni extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DigitaldevLx\LaravelMoloni\Moloni::class;
    }
}
