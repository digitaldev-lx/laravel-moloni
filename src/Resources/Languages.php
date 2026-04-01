<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class Languages extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'languages/getAll', description: 'List all languages')]
    public function getAll(): array
    {
        return $this->call(__FUNCTION__);
    }
}
