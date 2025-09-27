<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Plant\Department\Status;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\DepartmentFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'status', 'plant_id', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => [
                'value' => $value,
                'badge' => Status::tryFrom($value ?? null)?->badge(),
                'switch' => Status::tryFrom($value ?? null)?->switch(),
            ],
        );
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', Status::ACTIVE->value);
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(TenantUser::class, 'department_tenant_user', 'department_id', 'tuser_id')
            ->withPivot(['id', 'status', 'tenant_id'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->using(DepartmentTenantUser::class);
    }
}
