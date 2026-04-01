<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class Currencies extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'currencies/getAll', description: 'List all currencies')]
    public function getAll(): array
    {
        return $this->call(__FUNCTION__);
    }
}
