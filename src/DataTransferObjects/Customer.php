<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\DataTransferObjects;

use DigitaldevLx\LaravelMoloni\DataTransferObjects\Contracts\DataTransferObject;

final readonly class Customer implements DataTransferObject
{
    public function __construct(
        public string $vat,
        public string $number,
        public string $name,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $city = null,
        public ?string $zipCode = null,
        public ?int $countryId = null,
        public ?string $contactName = null,
        public ?string $contactEmail = null,
        public ?string $contactPhone = null,
        public ?int $maturityDateId = null,
        public ?int $paymentMethodId = null,
        public ?int $deliveryMethodId = null,
        public ?string $notes = null,
        public ?int $salespersonId = null,
        public ?float $discount = null,
        public ?int $languageId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'vat' => $this->vat,
            'number' => $this->number,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zipCode,
            'country_id' => $this->countryId,
            'contact_name' => $this->contactName,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'maturity_date_id' => $this->maturityDateId,
            'payment_method_id' => $this->paymentMethodId,
            'delivery_method_id' => $this->deliveryMethodId,
            'notes' => $this->notes,
            'salesperson_id' => $this->salespersonId,
            'discount' => $this->discount,
            'language_id' => $this->languageId,
        ], static fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            vat: $data['vat'],
            number: $data['number'],
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            zipCode: $data['zip_code'] ?? null,
            countryId: isset($data['country_id']) ? (int) $data['country_id'] : null,
            contactName: $data['contact_name'] ?? null,
            contactEmail: $data['contact_email'] ?? null,
            contactPhone: $data['contact_phone'] ?? null,
            maturityDateId: isset($data['maturity_date_id']) ? (int) $data['maturity_date_id'] : null,
            paymentMethodId: isset($data['payment_method_id']) ? (int) $data['payment_method_id'] : null,
            deliveryMethodId: isset($data['delivery_method_id']) ? (int) $data['delivery_method_id'] : null,
            notes: $data['notes'] ?? null,
            salespersonId: isset($data['salesperson_id']) ? (int) $data['salesperson_id'] : null,
            discount: isset($data['discount']) ? (float) $data['discount'] : null,
            languageId: isset($data['language_id']) ? (int) $data['language_id'] : null,
        );
    }
}
