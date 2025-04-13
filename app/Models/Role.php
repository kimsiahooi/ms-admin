<?php

namespace App\Models;

use App\Enums\ActivityLogs\LogNamesEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Activitylog\LogOptions;

class Role extends ModelsRole
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->useLogName(LogNamesEnum::Role->value);
    }
}
