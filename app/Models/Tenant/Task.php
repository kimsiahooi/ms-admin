<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Plant\Operation\Task\Status;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\TaskFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'status', 'operation_id', 'tenant_id'];

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

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(Route::class)
            ->withPivot(['id', 'tenant_id'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->using(RouteTask::class);;
    }
}
