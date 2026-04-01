<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class TaxExemptions extends Resource
{
    #[MoloniEndpoint(path: 'taxExemptions/getAll', description: 'List all tax exemptions')]
    public function getAll(): array
    {
        return $this->call(__FUNCTION__);
    }

    #[MoloniEndpoint(path: 'taxExemptions/getModifiedSince', description: 'Get tax exemptions modified since a given date')]
    public function getModifiedSince(string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['lastmodified' => $lastmodified]);
    }

    #[MoloniEndpoint(path: 'taxExemptions/countModifiedSince', description: 'Count tax exemptions modified since a given date')]
    public function countModifiedSince(string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['lastmodified' => $lastmodified]);
    }
}
