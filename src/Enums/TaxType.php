<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Enums;

enum TaxType: int
{
    case Percentage = 1;
    case Fixed = 2;

    public function label(): string
    {
        return match ($this) {
            self::Percentage => 'Percentagem',
            self::Fixed => 'Valor Fixo',
        };
    }
}
