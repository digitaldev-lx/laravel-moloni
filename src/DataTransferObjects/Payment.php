<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\DataTransferObjects;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Contracts\DataTransferObject;

final readonly class Payment implements DataTransferObject
{
    public function __construct(
        public int $paymentMethodId,
        public float $value,
        public ?string $date = null,
        public ?string $notes = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'payment_method_id' => $this->paymentMethodId,
            'value' => $this->value,
            'date' => $this->date,
            'notes' => $this->notes,
        ], static fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            paymentMethodId: (int) $data['payment_method_id'],
            value: (float) $data['value'],
            date: $data['date'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }
}
