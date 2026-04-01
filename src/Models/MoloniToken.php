<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Models;

use Illuminate\Database\Eloquent\Model;

final class MoloniToken extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
            'expires_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public static function current(): ?self
    {
        return self::query()->latest()->first();
    }
}
