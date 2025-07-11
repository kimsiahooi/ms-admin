<?php

namespace App\Models\Tenant;

use App\enums\tenant\Product\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'description', 'unit_price', 'is_active', 'material_id'];

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

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
