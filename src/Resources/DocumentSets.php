<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class DocumentSets extends Resource
{
    #[MoloniEndpoint(path: 'documentSets/getAll', description: 'List all document sets')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    #[MoloniEndpoint(path: 'documentSets/insert', description: 'Insert a document set')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'documentSets/update', description: 'Update a document set')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'documentSets/delete', description: 'Delete a document set')]
    public function delete(int $companyId, int $documentSetId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'document_set_id' => $documentSetId]);
    }

    #[MoloniEndpoint(path: 'documentSets/getModifiedSince', description: 'Get document sets modified since a given date')]
    public function getModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }

    #[MoloniEndpoint(path: 'documentSets/countModifiedSince', description: 'Count document sets modified since a given date')]
    public function countModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }
}
