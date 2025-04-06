<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SuperAdmin = 'super admin';
    case Admin = 'admin';
    case User = 'user';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::User => 'User',
        };
    }
}
