<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class ProductCategories extends Resource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'productCategories/getAll')]
    public function getAll(int $companyId, array $filters = []): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$filters]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'productCategories/getOne')]
    public function getOne(int $companyId, int $categoryId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'category_id' => $categoryId]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'productCategories/insert')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, ...$data]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'productCategories/update')]
    public function update(int $companyId, int $categoryId, array $data): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'category_id' => $categoryId, ...$data]);
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'productCategories/delete')]
    public function delete(int $companyId, int $categoryId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'category_id' => $categoryId]);
    }
}
