<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Price\Status;
use App\Traits\Tenant\StatusBadgeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPrice extends Model
{
    use BelongsToTenant, SoftDeletes, HasUlids, HasFactory, StatusBadgeTrait;

    protected $fillable = ['currency', 'amount', 'status', 'tenant_id', 'product_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['status_label'];

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->formatStatus($attributes['status'], Status::class),
        );
    }
}
