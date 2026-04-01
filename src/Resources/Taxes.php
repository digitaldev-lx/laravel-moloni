<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class Taxes extends Resource
{
    #[MoloniEndpoint(path: 'taxes/getAll', description: 'List all taxes')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    #[MoloniEndpoint(path: 'taxes/insert', description: 'Insert a tax')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'taxes/update', description: 'Update a tax')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'taxes/delete', description: 'Delete a tax')]
    public function delete(int $companyId, int $taxId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'tax_id' => $taxId]);
    }
}
