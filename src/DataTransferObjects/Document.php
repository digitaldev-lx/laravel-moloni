<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\DataTransferObjects;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Contracts\DataTransferObject;

final readonly class Document implements DataTransferObject
{
    /**
     * @param  array<DocumentProduct>  $products
     * @param  array<Payment>|null  $payments
     */
    public function __construct(
        public int $documentSetId,
        public int $customerId,
        public string $date,
        public string $expirationDate,
        public array $products = [],
        public ?array $payments = null,
        public ?string $ourReference = null,
        public ?string $yourReference = null,
        public ?string $notes = null,
        public ?int $salespersonId = null,
        public ?int $deliveryMethodId = null,
        public ?string $deliveryDatetime = null,
        public ?int $status = null,
        public ?float $financialDiscount = null,
        public ?float $specialDiscount = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'document_set_id' => $this->documentSetId,
            'customer_id' => $this->customerId,
            'date' => $this->date,
            'expiration_date' => $this->expirationDate,
            'our_reference' => $this->ourReference,
            'your_reference' => $this->yourReference,
            'notes' => $this->notes,
            'salesperson_id' => $this->salespersonId,
            'delivery_method_id' => $this->deliveryMethodId,
            'delivery_datetime' => $this->deliveryDatetime,
            'status' => $this->status,
            'financial_discount' => $this->financialDiscount,
            'special_discount' => $this->specialDiscount,
        ], static fn (mixed $value): bool => $value !== null);

        if ($this->products !== []) {
            $data['products'] = array_map(
                static fn (DocumentProduct $p): array => $p->toArray(),
                $this->products,
            );
        }

        if ($this->payments !== null) {
            $data['payments'] = array_map(
                static fn (Payment $p): array => $p->toArray(),
                $this->payments,
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
            documentSetId: (int) $data['document_set_id'],
            customerId: (int) $data['customer_id'],
            date: $data['date'],
            expirationDate: $data['expiration_date'],
            products: isset($data['products'])
                ? array_map(static fn (array $p): DocumentProduct => DocumentProduct::fromArray($p), $data['products'])
                : [],
            payments: isset($data['payments'])
                ? array_map(static fn (array $p): Payment => Payment::fromArray($p), $data['payments'])
                : null,
            ourReference: $data['our_reference'] ?? null,
            yourReference: $data['your_reference'] ?? null,
            notes: $data['notes'] ?? null,
            salespersonId: isset($data['salesperson_id']) ? (int) $data['salesperson_id'] : null,
            deliveryMethodId: isset($data['delivery_method_id']) ? (int) $data['delivery_method_id'] : null,
            deliveryDatetime: $data['delivery_datetime'] ?? null,
            status: isset($data['status']) ? (int) $data['status'] : null,
            financialDiscount: isset($data['financial_discount']) ? (float) $data['financial_discount'] : null,
            specialDiscount: isset($data['special_discount']) ? (float) $data['special_discount'] : null,
        );
    }
}
