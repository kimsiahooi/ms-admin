<?php

namespace App\enums\Tenant\Product\Bom;

enum Status: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function display(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }
}
