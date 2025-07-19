<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Machine\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Machine extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\MachineFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = ['name', 'code', 'description', 'is_active'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['is_active_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected function getIsActiveDisplayAttribute(): string
    {
        return Status::tryFrom($this->is_active)?->dislay();
    }
}
