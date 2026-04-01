<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Supplier as SupplierDto;

final class Suppliers extends Resource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/getAll')]
    public function getAll(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/getOne')]
    public function getOne(int $companyId, int $supplierId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'supplier_id' => $supplierId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/getByVat')]
    public function getByVat(int $companyId, string $vat): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'vat' => $vat]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/getByNumber')]
    public function getByNumber(int $companyId, string $number): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'number' => $number]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/getByName')]
    public function getByName(int $companyId, string $name): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'name' => $name]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/getBySearch')]
    public function search(int $companyId, string $search): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'search' => $search]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/count')]
    public function count(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/countBySearch')]
    public function countBySearch(int $companyId, string $search): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'search' => $search]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/countByVat')]
    public function countByVat(int $companyId, string $vat): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'vat' => $vat]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/countByNumber')]
    public function countByNumber(int $companyId, string $number): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'number' => $number]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/countByName')]
    public function countByName(int $companyId, string $name): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'name' => $name]);
    }

    /**
     * @param  SupplierDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/insert')]
    public function insert(int $companyId, SupplierDto|array $data): array
    {
        $params = $data instanceof SupplierDto ? $data->toArray() : $data;

        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$params]);
    }

    /**
     * @param  SupplierDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/update')]
    public function update(int $companyId, int $supplierId, SupplierDto|array $data): array
    {
        $params = $data instanceof SupplierDto ? $data->toArray() : $data;

        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'supplier_id' => $supplierId, ...$params]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'suppliers/delete')]
    public function delete(int $companyId, int $supplierId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'supplier_id' => $supplierId]);
    }
}
