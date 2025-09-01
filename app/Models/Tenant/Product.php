<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'status', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['status_label'];

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function presets()
    {
        return $this->hasMany(ProductPreset::class);
    }

    public function boms()
    {
        return $this->hasMany(Bom::class);
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Status::tryFrom($attributes['status'])?->label(),
        );
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', Status::ACTIVE->value);
    }
}
