<?php

namespace App\enums\Tenant\Machine;

enum Status: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function dislay(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }
}
