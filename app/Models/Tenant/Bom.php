<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Product\Bom\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Bom extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\BomFactory> */
    use HasFactory, BelongsToTenant, SoftDeletes, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'status', 'product_id', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['status_badge', 'status_switch'];

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Status::tryFrom($attributes['status'] ?? null)?->badge(),
        );
    }

    protected function statusSwitch(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Status::tryFrom($attributes['status'] ?? null)?->switch(),
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class)
            ->withPivot(['id', 'quantity', 'unit_type', 'tenant_id'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->using(BomMaterial::class);
    }
}
