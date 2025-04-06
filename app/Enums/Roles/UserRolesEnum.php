<?php

namespace App\Enums\Roles;

enum UserRolesEnum: string
{
    case SuperAdmin = 'Super Admin';
    case Admin = 'Admin';
    case User = 'User';

    // public function label(): string
    // {
    //     return match ($this) {
    //         self::SuperAdmin => 'Super Admin',
    //         self::Admin => 'Admin',
    //         self::User => 'User',
    //     };
    // }
}
