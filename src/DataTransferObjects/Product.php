<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\DataTransferObjects;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Contracts\DataTransferObject;
use DigitaldevLx\LaravelMoloni\Enums\ProductType;

final readonly class Product implements DataTransferObject
{
    /**
     * @param  array<Tax>|null  $taxes
     */
    public function __construct(
        public string $name,
        public string $reference,
        public ProductType $type,
        public int $categoryId,
        public int $unitId,
        public float $price,
        public ?string $ean = null,
        public ?string $summary = null,
        public ?int $warehouseId = null,
        public ?float $stock = null,
        public ?float $minimumStock = null,
        public ?int $hasStock = null,
        public ?int $atProductCategory = null,
        public ?array $taxes = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'name' => $this->name,
            'reference' => $this->reference,
            'type' => $this->type->value,
            'category_id' => $this->categoryId,
            'unit_id' => $this->unitId,
            'price' => $this->price,
            'ean' => $this->ean,
            'summary' => $this->summary,
            'warehouse_id' => $this->warehouseId,
            'stock' => $this->stock,
            'minimum_stock' => $this->minimumStock,
            'has_stock' => $this->hasStock,
            'at_product_category' => $this->atProductCategory,
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
        return new static(
            name: $data['name'],
            reference: $data['reference'],
            type: ProductType::from((int) $data['type']),
            categoryId: (int) $data['category_id'],
            unitId: (int) $data['unit_id'],
            price: (float) $data['price'],
            ean: $data['ean'] ?? null,
            summary: $data['summary'] ?? null,
            warehouseId: isset($data['warehouse_id']) ? (int) $data['warehouse_id'] : null,
            stock: isset($data['stock']) ? (float) $data['stock'] : null,
            minimumStock: isset($data['minimum_stock']) ? (float) $data['minimum_stock'] : null,
            hasStock: isset($data['has_stock']) ? (int) $data['has_stock'] : null,
            atProductCategory: isset($data['at_product_category']) ? (int) $data['at_product_category'] : null,
            taxes: isset($data['taxes'])
                ? array_map(static fn (array $t): Tax => Tax::fromArray($t), $data['taxes'])
                : null,
        );
    }
}
