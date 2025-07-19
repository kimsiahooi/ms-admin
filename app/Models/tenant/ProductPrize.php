<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrize extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\ProductPrizeFactory> */
    use HasFactory;

    protected $fillable = ['currency', 'prize'];
}
