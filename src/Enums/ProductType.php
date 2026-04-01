<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Enums;

enum ProductType: int
{
    case Product = 1;
    case Service = 2;
    case Other = 3;
    case TaxesFees = 4;

    public function label(): string
    {
        return match ($this) {
            self::Product => 'Produto',
            self::Service => 'Serviço',
            self::Other => 'Outro',
            self::TaxesFees => 'Impostos/Taxas',
        };
    }
}
