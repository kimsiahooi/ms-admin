<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPrice extends Model
{
    use BelongsToTenant, SoftDeletes, HasUlids, HasFactory;

    protected $fillable = ['currency', 'amount', 'tenant_id', 'product_id'];

    protected $hidden = ['tenant_id'];
}
