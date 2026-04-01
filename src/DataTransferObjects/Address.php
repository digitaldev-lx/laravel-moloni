<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\DataTransferObjects;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Contracts\DataTransferObject;

final readonly class Address implements DataTransferObject
{
    public function __construct(
        public ?string $address = null,
        public ?string $city = null,
        public ?string $zipCode = null,
        public ?int $countryId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zipCode,
            'country_id' => $this->countryId,
        ], static fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            zipCode: $data['zip_code'] ?? null,
            countryId: isset($data['country_id']) ? (int) $data['country_id'] : null,
        );
    }
}
