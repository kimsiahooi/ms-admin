<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Preset\CavityType;
use App\enums\Tenant\Product\Preset\CycleTimeType;
use App\enums\Tenant\Product\Preset\Status;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPreset extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['product_id', 'machine_id', 'is_active', 'name', 'description', 'cavity_quantity', 'cavity_type', 'cycle_time', 'cycle_time_type'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['is_active_display', 'cavity_type_display', 'cycle_time_type_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected function getCavityTypeDisplayAttribute(): string | null
    {
        return CavityType::tryFrom($this->cavity_type)?->display();
    }

    protected function getCycleTimeTypeDisplayAttribute(): string | null
    {
        return CycleTimeType::tryFrom($this->cycle_time_type)?->display();
    }

    protected function getIsActiveDisplayAttribute(): string | null
    {
        return Status::tryFrom($this->is_active)?->display();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
