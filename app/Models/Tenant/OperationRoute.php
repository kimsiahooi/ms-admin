<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class OperationRoute extends Pivot
{
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['route_id', 'operation_id', 'sequence', 'tenant_id'];

    protected $hidden = ['tenant_id'];
}
