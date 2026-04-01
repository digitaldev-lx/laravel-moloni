<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\DataTransferObjects;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Contracts\DataTransferObject;

final readonly class DocumentProduct implements DataTransferObject
{
    /**
     * @param  array<Tax>|null  $taxes
     */
    public function __construct(
        public int $productId,
        public float $qty,
        public float $price,
        public ?string $name = null,
        public ?string $summary = null,
        public ?float $discount = null,
        public ?int $exemptionReason = null,
        public ?int $warehouseId = null,
        public ?array $taxes = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'product_id' => $this->productId,
            'qty' => $this->qty,
            'price' => $this->price,
            'name' => $this->name,
            'summary' => $this->summary,
            'discount' => $this->discount,
            'exemption_reason' => $this->exemptionReason,
            'warehouse_id' => $this->warehouseId,
        ], static fn (mixed $value): bool => $value !== null);

        if ($this->taxes !== null) {
            $data['taxes'] = array_map(
                static fn (Tax $tax): array => $tax->toArray(),
                $this->taxes,
            );
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            productId: (int) $data['product_id'],
            qty: (float) $data['qty'],
            price: (float) $data['price'],
            name: $data['name'] ?? null,
            summary: $data['summary'] ?? null,
            discount: isset($data['discount']) ? (float) $data['discount'] : null,
            exemptionReason: isset($data['exemption_reason']) ? (int) $data['exemption_reason'] : null,
            warehouseId: isset($data['warehouse_id']) ? (int) $data['warehouse_id'] : null,
            taxes: isset($data['taxes'])
                ? array_map(static fn (array $t): Tax => Tax::fromArray($t), $data['taxes'])
                : null,
        );
    }
}
