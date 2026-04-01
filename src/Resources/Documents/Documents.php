<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources\Documents;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;
use DigitaldevLx\LaravelMoloni\Resources\Resource;

final class Documents extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'documents/getAllDocumentTypes')]
    public function getAllDocumentTypes(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'documents/getAll')]
    public function getAll(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'documents/getOne')]
    public function getOne(int $companyId, int $documentId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'document_id' => $documentId]);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'documents/count')]
    public function count(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'documents/getPDFLink')]
    public function getPdfLink(int $companyId, int $documentId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'document_id' => $documentId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'documents/getModifiedSince')]
    public function getModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'documents/countModifiedSince')]
    public function countModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }
}
