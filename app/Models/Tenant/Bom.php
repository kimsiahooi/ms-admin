<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Product\Bom\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Bom extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\BomFactory> */
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $fillables = ['name', 'code', 'description', 'is_active', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['is_active_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean'
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot(['quantity'])->withTimestamps()->using(BomMaterial::class);
    }

    protected function getIsActiveDisplayAttribute(): string | null
    {
        return Status::tryFrom($this->is_active)?->display();
    }
}
