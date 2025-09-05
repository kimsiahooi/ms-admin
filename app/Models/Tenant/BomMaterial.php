<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class BomMaterial extends Pivot
{
    /** @use HasFactory<\Database\Factories\Tenant\BomMaterialFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['quantity', 'unit_type', 'tenant_id', 'bom_id', 'material_id'];

    protected $hidden = ['tenant_id'];
}
