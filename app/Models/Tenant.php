<?php

namespace App\Models;

use App\Enums\Admin\Tenant\Status;
use App\Traits\Tenant\StatusBadgeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory, SoftDeletes, StatusBadgeTrait;

    protected $appends = ['status_label'];

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->formatStatus($attributes['status'], Status::class),
        );
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'status',
            'created_at',
            'updated_at',
        ];
    }
}
