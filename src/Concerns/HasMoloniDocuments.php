<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Concerns;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Document as DocumentDto;
use DigitaldevLx\LaravelMoloni\Facades\Moloni;

trait HasMoloniDocuments
{
    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createMoloniInvoice(int $companyId, DocumentDto|array $data): array
    {
        return Moloni::invoices()->insert($companyId, $data);
    }

    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createMoloniReceipt(int $companyId, DocumentDto|array $data): array
    {
        return Moloni::receipts()->insert($companyId, $data);
    }

    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createMoloniInvoiceReceipt(int $companyId, DocumentDto|array $data): array
    {
        return Moloni::invoiceReceipts()->insert($companyId, $data);
    }

    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createMoloniSimplifiedInvoice(int $companyId, DocumentDto|array $data): array
    {
        return Moloni::simplifiedInvoices()->insert($companyId, $data);
    }

    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createMoloniCreditNote(int $companyId, DocumentDto|array $data): array
    {
        return Moloni::creditNotes()->insert($companyId, $data);
    }

    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createMoloniEstimate(int $companyId, DocumentDto|array $data): array
    {
        return Moloni::estimates()->insert($companyId, $data);
    }
}
