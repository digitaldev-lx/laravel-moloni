<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class MaturityDates extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'maturityDates/getAll', description: 'List all maturity dates')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'maturityDates/insert', description: 'Insert a maturity date')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'maturityDates/update', description: 'Update a maturity date')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'maturityDates/delete', description: 'Delete a maturity date')]
    public function delete(int $companyId, int $maturityDateId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'maturity_date_id' => $maturityDateId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'maturityDates/getModifiedSince', description: 'Get maturity dates modified since a given date')]
    public function getModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'maturityDates/countModifiedSince', description: 'Count maturity dates modified since a given date')]
    public function countModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }
}
