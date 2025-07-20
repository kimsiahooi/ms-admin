<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Material\Status;
use App\enums\Tenant\Material\UnitType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\MaterialFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = ['name', 'code', 'description', 'unit_type', 'is_active', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['unit_type_display', 'is_active_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    protected function getUnitTypeDisplayAttribute(): string | null
    {
        return UnitType::tryFrom($this->unit_type)?->display();
    }

    protected function getIsActiveDisplayAttribute(): string | null
    {
        return Status::tryFrom($this->is_active)?->display();
    }


    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
