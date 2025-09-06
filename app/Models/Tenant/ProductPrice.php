<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Price\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPrice extends Model
{
    use BelongsToTenant, SoftDeletes, HasUlids, HasFactory;

    protected $fillable = ['currency', 'amount', 'status', 'tenant_id', 'product_id'];

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
}
