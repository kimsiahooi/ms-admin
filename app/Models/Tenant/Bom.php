<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Bom\Status;
use App\Traits\Tenant\StatusBadgeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Bom extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\BomFactory> */
    use HasFactory, BelongsToTenant, SoftDeletes, HasUlids, StatusBadgeTrait;

    protected $fillable = ['name', 'code', 'description', 'status', 'product_id', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['status_label'];

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->formatStatus($attributes['status'], Status::class),
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class)
            ->withPivot(['id', 'quantity', 'unit_type'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->using(BomMaterial::class);
    }
}
