<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Customer as CustomerDto;
use DigitaldevLx\LaravelMoloni\Events\CustomerCreated;
use DigitaldevLx\LaravelMoloni\Events\CustomerUpdated;

final class Customers extends Resource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getAll')]
    public function getAll(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getOne')]
    public function getOne(int $companyId, int $customerId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'customer_id' => $customerId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getByVat')]
    public function getByVat(int $companyId, string $vat): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'vat' => $vat]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getByEmail')]
    public function getByEmail(int $companyId, string $email): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'email' => $email]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getByNumber')]
    public function getByNumber(int $companyId, string $number): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'number' => $number]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getByName')]
    public function getByName(int $companyId, string $name): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'name' => $name]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getBySearch')]
    public function search(int $companyId, string $search): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'search' => $search]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getNextNumber')]
    public function getNextNumber(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getLastNumber')]
    public function getLastNumber(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/getModifiedSince')]
    public function getModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/count')]
    public function count(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/countBySearch')]
    public function countBySearch(int $companyId, string $search): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'search' => $search]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/countByVat')]
    public function countByVat(int $companyId, string $vat): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'vat' => $vat]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/countByNumber')]
    public function countByNumber(int $companyId, string $number): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'number' => $number]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/countByEmail')]
    public function countByEmail(int $companyId, string $email): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'email' => $email]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/countByName')]
    public function countByName(int $companyId, string $name): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'name' => $name]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/countModifiedSince')]
    public function countModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }

    /**
     * @param  CustomerDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/insert')]
    public function insert(int $companyId, CustomerDto|array $data): array
    {
        $params = $data instanceof CustomerDto ? $data->toArray() : $data;
        $result = $this->call(__FUNCTION__, ['company_id' => $companyId, ...$params]);

        event(new CustomerCreated($result));

        return $result;
    }

    /**
     * @param  CustomerDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/update')]
    public function update(int $companyId, int $customerId, CustomerDto|array $data): array
    {
        $params = $data instanceof CustomerDto ? $data->toArray() : $data;
        $result = $this->call(__FUNCTION__, ['company_id' => $companyId, 'customer_id' => $customerId, ...$params]);

        event(new CustomerUpdated($result));

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'customers/delete')]
    public function delete(int $companyId, int $customerId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'customer_id' => $customerId]);
    }
}
