<?php

namespace App\Models\Tenant;

use App\enums\Tenant\Material\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\MaterialFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = ['name', 'code', 'description', 'is_active', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['is_active_display'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    protected function getIsActiveDisplayAttribute(): string
    {
        return Status::tryFrom($this->is_active)?->dislay();
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
