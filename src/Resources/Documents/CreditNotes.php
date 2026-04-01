<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources\Documents;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Document as DocumentDto;
use DigitaldevLx\LaravelMoloni\Events\DocumentCreated;
use DigitaldevLx\LaravelMoloni\Resources\Resource;

final class CreditNotes extends Resource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'creditNotes/getAll')]
    public function getAll(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'creditNotes/getOne')]
    public function getOne(int $companyId, int $documentId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'document_id' => $documentId]);
    }

    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'creditNotes/insert')]
    public function insert(int $companyId, DocumentDto|array $data): array
    {
        $params = $data instanceof DocumentDto ? $data->toArray() : $data;
        $result = $this->call(__FUNCTION__, ['company_id' => $companyId, ...$params]);
        event(new DocumentCreated($result, 'creditNotes'));

        return $result;
    }

    /**
     * @param  DocumentDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'creditNotes/update')]
    public function update(int $companyId, int $documentId, DocumentDto|array $data): array
    {
        $params = $data instanceof DocumentDto ? $data->toArray() : $data;

        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'document_id' => $documentId, ...$params]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'creditNotes/delete')]
    public function delete(int $companyId, int $documentId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'document_id' => $documentId]);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'creditNotes/count')]
    public function count(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'creditNotes/getPDFLink')]
    public function getPdfLink(int $companyId, int $documentId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'document_id' => $documentId]);
    }
}
