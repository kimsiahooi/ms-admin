<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Route\Status;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Route extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\RouteFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'status', 'tenant_id'];

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

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class)
            ->withPivot(['id', 'tenant_id'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->using(OperationRoute::class);
    }

    public function boms(): BelongsToMany
    {
        return $this->belongsToMany(Bom::class)
            ->withPivot(['id', 'status', 'tenant_id'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->using(BomRoute::class);
    }
}
