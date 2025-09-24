<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Product\Preset\ProductType;
use App\Enums\Tenant\Product\Preset\CycleTimeType;
use App\Enums\Tenant\Product\Preset\ShelfLifeType;
use App\Enums\Tenant\Product\Preset\Status;
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

    protected $fillable = ['product_id', 'name', 'code', 'description', 'quantity', 'product_type', 'cycle_time', 'cycle_time_type', 'shelf_life_duration', 'shelf_life_type', 'status', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['cavity_type_label', 'cycle_time_type_label', 'shelf_life_type_label'];

    protected function cavityTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => ProductType::tryFrom($attributes['product_type'])?->label(),
        );
    }

    protected function cycleTimeTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => CycleTimeType::tryFrom($attributes['cycle_time_type'])?->label(),
        );
    }

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
}
