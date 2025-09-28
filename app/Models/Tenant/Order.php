<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\OrderFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['customer_branch_id', 'currency', 'remark', 'delivery_date', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $casts = [
        'delivery_date' => 'datetime'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(CustomerBranch::class);
    }
}
