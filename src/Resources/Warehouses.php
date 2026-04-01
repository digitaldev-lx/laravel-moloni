<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class Warehouses extends Resource
{
    #[MoloniEndpoint(path: 'warehouses/getAll', description: 'List all warehouses')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    #[MoloniEndpoint(path: 'warehouses/insert', description: 'Insert a warehouse')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'warehouses/update', description: 'Update a warehouse')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'warehouses/delete', description: 'Delete a warehouse')]
    public function delete(int $companyId, int $warehouseId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'warehouse_id' => $warehouseId]);
    }
}
