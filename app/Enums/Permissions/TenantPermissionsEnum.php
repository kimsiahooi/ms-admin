<?php

namespace App\Enums\Permissions;

enum TenantPermissionsEnum: string
{
    case ViewTenant = 'View Tenant';
    case CreateTenant = 'Create Tenant';
    case EditTenant = 'Edit Tenant';
    case DeleteTenant = 'Force Delete Tenant';
}
