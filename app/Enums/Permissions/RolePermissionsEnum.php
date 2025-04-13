<?php

namespace App\Enums\Permissions;

enum RolePermissionsEnum: string
{
    case ViewRole = 'View Role';
    case CreateRole = 'Create Role';
    case EditRole = 'Edit Role';
    case FoceDeleteRole = 'Force Delete Role';
    case AuditRole = 'Audit Role';
}
