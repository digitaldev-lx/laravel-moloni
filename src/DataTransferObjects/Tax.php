<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\DataTransferObjects;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Contracts\DataTransferObject;

final readonly class Tax implements DataTransferObject
{
    public function __construct(
        public int $taxId,
        public float $value,
        public ?int $order = null,
        public ?int $cumulative = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'tax_id' => $this->taxId,
            'value' => $this->value,
            'order' => $this->order,
            'cumulative' => $this->cumulative,
        ], static fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            taxId: (int) $data['tax_id'],
            value: (float) $data['value'],
            order: isset($data['order']) ? (int) $data['order'] : null,
            cumulative: isset($data['cumulative']) ? (int) $data['cumulative'] : null,
        );
    }
}
