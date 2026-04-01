<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;

final class BankAccounts extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'bankAccounts/getAll', description: 'List all bank accounts')]
    public function getAll(int $companyId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'bankAccounts/insert', description: 'Insert a bank account')]
    public function insert(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'bankAccounts/update', description: 'Update a bank account')]
    public function update(int $companyId, array $data): array
    {
        return $this->call(__FUNCTION__, array_merge(['company_id' => $companyId], $data));
    }

    /**
     * @return array<string, mixed>
     */
    #[MoloniEndpoint(path: 'bankAccounts/delete', description: 'Delete a bank account')]
    public function delete(int $companyId, int $bankAccountId): array
    {
        return $this->call(__FUNCTION__, ['company_id' => $companyId, 'bank_account_id' => $bankAccountId]);
    }
}
