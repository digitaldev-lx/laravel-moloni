<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Enums;

enum DocumentStatus: int
{
    case Draft = 0;
    case Closed = 1;
    case Cancelled = 2;

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Closed => 'Fechado',
            self::Cancelled => 'Anulado',
        };
    }
}
