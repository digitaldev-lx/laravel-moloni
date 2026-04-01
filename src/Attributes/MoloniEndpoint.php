<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class MoloniEndpoint
{
    public function __construct(
        public string $path,
        public string $description = '',
    ) {}
}
