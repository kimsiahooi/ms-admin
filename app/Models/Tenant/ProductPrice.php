<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPrice extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = ['currency', 'amount', 'tenant_id'];

    protected $hidden = ['tenant_id'];
}
