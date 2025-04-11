<?php

namespace App\Enums\ActivityLogs;

enum LogNamesEnum: string
{
    case Role = 'Role';
    case User = 'User';
    case Tenant = 'Tenant';
}
