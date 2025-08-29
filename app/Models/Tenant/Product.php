<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\ShelfLifeType;
use App\enums\Tenant\Product\Status;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'shelf_life_duration', 'shelf_life_type', 'is_active', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['is_active_display', 'shelf_life_type_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

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

    protected function getIsActiveDisplayAttribute(): string | null
    {
        return Status::tryFrom($this->is_active)?->display();
    }

    protected function getShelfLifeTypeDisplayAttribute(): string | null
    {
        return ShelfLifeType::tryFrom($this->shelf_life_type)?->display();
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
