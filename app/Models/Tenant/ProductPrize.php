<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPrize extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\ProductPrizeFactory> */
    use HasFactory, BelongsToTenant;

    protected $fillable = ['currency', 'prize'];
}
