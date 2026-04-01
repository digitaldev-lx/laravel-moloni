<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class Countries extends Resource
{
    #[MoloniEndpoint(path: 'countries/getAll', description: 'List all countries')]
    public function getAll(): array
    {
        return $this->call(__FUNCTION__);
    }
}
