<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Plant\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Plant extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\PlantFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'address', 'status', 'tneant_id'];

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

    public function scopeActive(Builder $query): void
    {
        $query->where('status', Status::ACTIVE->value);
    }

    public function tenantUsers(): BelongsToMany
    {
        return $this->belongsToMany(TenantUser::class)
            ->withPivot(['id', 'tenant_id'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->using(PlantTenantUser::class);
    }
}
