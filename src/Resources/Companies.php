<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class Companies extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'companies/getAll')]
    public function getAll(): array
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'companies/getOne')]
    public function getOne(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }
}
