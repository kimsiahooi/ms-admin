<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Material\Status;
use App\enums\Tenant\Material\UnitType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\MaterialFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'unit_type', 'status', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['unit_type_label', 'status_badge', 'status_switch'];

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

    public function boms(): BelongsToMany
    {
        return $this->belongsToMany(Bom::class)->withTimestamps()->using(BomMaterial::class);
    }

    protected function unitTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => UnitType::tryFrom($attributes['unit_type'] ?? null)?->label(),
        );
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', Status::ACTIVE->value);
    }
}
