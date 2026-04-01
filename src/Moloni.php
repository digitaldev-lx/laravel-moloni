<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni;

use DigitaldevLx\LaravelMoloni\Http\MoloniClient;
use DigitaldevLx\LaravelMoloni\Resources;

final class Moloni
{
    /** @var array<class-string<Resources\Resource>, Resources\Resource> */
    private array $resources = [];

    public function __construct(
        private readonly MoloniClient $client,
    ) {}

    public function client(): MoloniClient
    {
        return $this->client;
    }

    public function companies(): Resources\Companies
    {
        return $this->resolve(Resources\Companies::class);
    }

    public function customers(): Resources\Customers
    {
        return $this->resolve(Resources\Customers::class);
    }

    public function suppliers(): Resources\Suppliers
    {
        return $this->resolve(Resources\Suppliers::class);
    }

    public function products(): Resources\Products
    {
        return $this->resolve(Resources\Products::class);
    }

    public function productCategories(): Resources\ProductCategories
    {
        return $this->resolve(Resources\ProductCategories::class);
    }

    public function invoices(): Resources\Documents\Invoices
    {
        return $this->resolve(Resources\Documents\Invoices::class);
    }

    public function receipts(): Resources\Documents\Receipts
    {
        return $this->resolve(Resources\Documents\Receipts::class);
    }

    public function creditNotes(): Resources\Documents\CreditNotes
    {
        return $this->resolve(Resources\Documents\CreditNotes::class);
    }

    public function debitNotes(): Resources\Documents\DebitNotes
    {
        return $this->resolve(Resources\Documents\DebitNotes::class);
    }

    public function simplifiedInvoices(): Resources\Documents\SimplifiedInvoices
    {
        return $this->resolve(Resources\Documents\SimplifiedInvoices::class);
    }

    public function invoiceReceipts(): Resources\Documents\InvoiceReceipts
    {
        return $this->resolve(Resources\Documents\InvoiceReceipts::class);
    }

    public function deliveryNotes(): Resources\Documents\DeliveryNotes
    {
        return $this->resolve(Resources\Documents\DeliveryNotes::class);
    }

    public function billsOfLading(): Resources\Documents\BillsOfLading
    {
        return $this->resolve(Resources\Documents\BillsOfLading::class);
    }

    public function waybills(): Resources\Documents\Waybills
    {
        return $this->resolve(Resources\Documents\Waybills::class);
    }

    public function estimates(): Resources\Documents\Estimates
    {
        return $this->resolve(Resources\Documents\Estimates::class);
    }

    public function documents(): Resources\Documents\Documents
    {
        return $this->resolve(Resources\Documents\Documents::class);
    }

    public function taxes(): Resources\Taxes
    {
        return $this->resolve(Resources\Taxes::class);
    }

    public function paymentMethods(): Resources\PaymentMethods
    {
        return $this->resolve(Resources\PaymentMethods::class);
    }

    public function documentSets(): Resources\DocumentSets
    {
        return $this->resolve(Resources\DocumentSets::class);
    }

    public function warehouses(): Resources\Warehouses
    {
        return $this->resolve(Resources\Warehouses::class);
    }

    public function measurementUnits(): Resources\MeasurementUnits
    {
        return $this->resolve(Resources\MeasurementUnits::class);
    }

    public function maturityDates(): Resources\MaturityDates
    {
        return $this->resolve(Resources\MaturityDates::class);
    }

    public function deliveryMethods(): Resources\DeliveryMethods
    {
        return $this->resolve(Resources\DeliveryMethods::class);
    }

    public function bankAccounts(): Resources\BankAccounts
    {
        return $this->resolve(Resources\BankAccounts::class);
    }

    public function countries(): Resources\Countries
    {
        return $this->resolve(Resources\Countries::class);
    }

    public function fiscalZones(): Resources\FiscalZones
    {
        return $this->resolve(Resources\FiscalZones::class);
    }

    public function languages(): Resources\Languages
    {
        return $this->resolve(Resources\Languages::class);
    }

    public function currencies(): Resources\Currencies
    {
        return $this->resolve(Resources\Currencies::class);
    }

    public function taxExemptions(): Resources\TaxExemptions
    {
        return $this->resolve(Resources\TaxExemptions::class);
    }

    /**
     * @template T of Resources\Resource
     *
     * @param  class-string<T>  $class
     * @return T
     */
    private function resolve(string $class): Resources\Resource
    {
        /** @var T */
        return $this->resources[$class] ??= new $class($this->client);
    }
}
