<?php

namespace App\Enums\Permissions;

enum UserPermissionsEnum: string
{
    case ViewUser = 'View User';
    case CreateUser = 'Create User';
    case UpdateUser = 'Update User';
    case DeleteUser = 'Delete User';
    case RestoreUser = 'Restore User';
    case ForceDeleteUser = 'Force Delete User';
}
