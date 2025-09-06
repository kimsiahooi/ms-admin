<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Preset\CavityType;
use App\enums\Tenant\Product\Preset\CycleTimeType;
use App\enums\Tenant\Product\Preset\ShelfLifeType;
use App\enums\Tenant\Product\Preset\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ProductPreset extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['product_id', 'machine_id', 'name', 'code', 'description', 'cavity_quantity', 'cavity_type', 'cycle_time', 'cycle_time_type', 'shelf_life_duration', 'shelf_life_type', 'status', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['status_badge', 'status_switch', 'cavity_type_label', 'cycle_time_type_label', 'shelf_life_type_label'];

    protected function cavityTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => CavityType::tryFrom($attributes['cavity_type'])?->label(),
        );
    }

    protected function cycleTimeTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => CycleTimeType::tryFrom($attributes['cycle_time_type'])?->label(),
        );
    }

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

    protected function shelfLifeTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => ShelfLifeType::tryFrom($attributes['shelf_life_type'])?->label(),
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }
}
