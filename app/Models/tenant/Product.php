<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = ['name', 'code', 'description', 'shelf_life_days', 'is_active'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['is_active_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class)->withTimestamps();
    }

    public function prizes()
    {
        return $this->hasMany(ProductPrize::class);
    }

    protected function getIsActiveDisplayAttribute(): string
    {
        return Status::tryFrom($this->is_active)?->dislay();
    }
}
