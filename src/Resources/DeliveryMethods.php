<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class DeliveryMethods extends Resource
{
    #[MoloniEndpoint(path: 'deliveryMethods/getAll', description: 'List all delivery methods')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    #[MoloniEndpoint(path: 'deliveryMethods/insert', description: 'Insert a delivery method')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'deliveryMethods/update', description: 'Update a delivery method')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    #[MoloniEndpoint(path: 'deliveryMethods/delete', description: 'Delete a delivery method')]
    public function delete(int $companyId, int $deliveryMethodId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'delivery_method_id' => $deliveryMethodId]);
    }
}
