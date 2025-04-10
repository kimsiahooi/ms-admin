<?php

namespace App\Enums\Permissions;

enum UserPermissionsEnum: string
{
    case ViewUser = 'View User';
    case CreateUser = 'Create User';
    case EditUser = 'Edit User';
    case DeleteUser = 'Delete User';
    case ViewTrashedUser = 'View Trashed User';
    case RestoreUser = 'Restore User';
    case ForceDeleteUser = 'Force Delete User';
    case AuditUser = 'Audit User';
}
