<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPrice extends Model
{
    use BelongsToTenant;

    protected $fillable = ['currency', 'price'];
}
