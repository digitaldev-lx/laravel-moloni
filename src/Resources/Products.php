<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Product as ProductDto;
use DigitaldevLx\LaravelMoloni\Events\ProductCreated;
use DigitaldevLx\LaravelMoloni\Events\ProductUpdated;

final class Products extends Resource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getAll')]
    public function getAll(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getOne')]
    public function getOne(int $companyId, int $productId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'product_id' => $productId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getBySearch')]
    public function getBySearch(int $companyId, string $search): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'search' => $search]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getByName')]
    public function getByName(int $companyId, string $name): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'name' => $name]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getByReference')]
    public function getByReference(int $companyId, string $reference): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'reference' => $reference]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getByEAN')]
    public function getByEan(int $companyId, string $ean): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'ean' => $ean]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getModifiedSince')]
    public function getModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getLastCostPrice')]
    public function getLastCostPrice(int $companyId, int $productId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'product_id' => $productId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/getNextReference')]
    public function getNextReference(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/count')]
    public function count(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/countBySearch')]
    public function countBySearch(int $companyId, string $search): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'search' => $search]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/countByName')]
    public function countByName(int $companyId, string $name): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'name' => $name]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/countByReference')]
    public function countByReference(int $companyId, string $reference): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'reference' => $reference]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/countByEAN')]
    public function countByEan(int $companyId, string $ean): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'ean' => $ean]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/countModifiedSince')]
    public function countModifiedSince(int $companyId, string $lastmodified): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'lastmodified' => $lastmodified]);
    }

    /**
     * @param  ProductDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/insert')]
    public function insert(int $companyId, ProductDto|array $data): array
    {
        $params = $data instanceof ProductDto ? $data->toArray() : $data;
        $result = $this->call(__FUNCTION__, ['company_id' => $companyId, ...$params]);

        event(new ProductCreated($result));

        return $result;
    }

    /**
     * @param  ProductDto|array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/update')]
    public function update(int $companyId, int $productId, ProductDto|array $data): array
    {
        $params = $data instanceof ProductDto ? $data->toArray() : $data;
        $result = $this->call(__FUNCTION__, ['company_id' => $companyId, 'product_id' => $productId, ...$params]);

        event(new ProductUpdated($result));

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'products/delete')]
    public function delete(int $companyId, int $productId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'product_id' => $productId]);
    }
}
