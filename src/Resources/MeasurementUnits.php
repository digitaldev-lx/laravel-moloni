<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class MeasurementUnits extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'measurementUnits/getAll', description: 'List all measurement units')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'measurementUnits/insert', description: 'Insert a measurement unit')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'measurementUnits/update', description: 'Update a measurement unit')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'measurementUnits/delete', description: 'Delete a measurement unit')]
    public function delete(int $companyId, int $unitId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'unit_id' => $unitId]);
    }
}
