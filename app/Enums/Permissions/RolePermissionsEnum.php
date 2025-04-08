<?php

namespace App\Enums\Permissions;

enum RolePermissionsEnum: string
{
    case ViewRole = 'View Role';
    case CreateRole = 'Create Role';
    case UpdateRole = 'Update Role';
    case FoceDeleteRole = 'Force Delete Role';
}
