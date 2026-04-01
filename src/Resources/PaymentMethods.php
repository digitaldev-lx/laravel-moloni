<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class PaymentMethods extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'paymentMethods/getAll', description: 'List all payment methods')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'paymentMethods/insert', description: 'Insert a payment method')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'paymentMethods/update', description: 'Update a payment method')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'paymentMethods/delete', description: 'Delete a payment method')]
    public function delete(int $companyId, int $paymentMethodId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'payment_method_id' => $paymentMethodId]);
    }
}
