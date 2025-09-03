<?php

namespace App\enums\Tenant\Product;

enum Status: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }

    public function variant(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'destructive',
            default => 'default',
        };
    }
}
