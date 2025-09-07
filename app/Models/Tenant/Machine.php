<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Machine\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Machine extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\MachineFactory> */
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

    public function scopeActive(Builder $query): void
    {
        $query->where('status', Status::ACTIVE->value);
    }
}
