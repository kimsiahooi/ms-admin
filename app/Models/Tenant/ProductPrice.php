<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Price\Status;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPrice extends Model
{
    use BelongsToTenant, SoftDeletes, HasUlids, HasFactory;

    protected $fillable = ['currency', 'amount', 'is_active', 'tenant_id', 'product_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['is_active_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected function getIsActiveDisplayAttribute(): string | null
    {
        return Status::tryFrom($this->is_active)?->display();
    }
}
