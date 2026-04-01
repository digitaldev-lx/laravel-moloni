<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class FiscalZones extends Resource
{
    #[MoloniEndpoint(path: 'fiscalZones/getAll', description: 'List all fiscal zones for a country')]
    public function getAll(int $countryId): array
    {
        return $this->call(__FUNCTION__, ['country_id' => $countryId]);
    }
}
