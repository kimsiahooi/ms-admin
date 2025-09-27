<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Plant\Department\TenantUser\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class DepartmentTenantUser extends Pivot
{
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['status', 'department_id', 'tuser_id', 'tenant_id'];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(TenantUser::class, 'tuser_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
