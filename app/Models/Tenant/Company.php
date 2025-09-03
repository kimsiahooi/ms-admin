<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Company\Status;
use App\Traits\Tenant\StatusBadgeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\Tenant\CompanyFactory> */
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids, StatusBadgeTrait;

    protected $fillable = ['name', 'code', 'description', 'status', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['status_label'];

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->formatStatus($attributes['status'], Status::class),
        );
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', Status::ACTIVE->value);
    }

    public function branches()
    {
        return $this->hasMany(CompanyBranch::class);
    }
}
