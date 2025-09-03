<?php

namespace App\Models\Tenant;

use App\Enums\Tenant\Company\Status;
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
    use HasFactory, SoftDeletes, BelongsToTenant, HasUlids;

    protected $fillable = ['name', 'code', 'description', 'status', 'tenant_id'];

    protected $hidden = ['tenant_id'];

    protected $appends = ['status_label'];

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $status = $attributes['status'];

                switch ($status) {
                    case Status::ACTIVE->value:
                        $variant = 'success';
                        break;
                    case Status::INACTIVE->value:
                        $variant = 'destructive';
                        break;
                    default:
                        $variant = 'default';
                        break;
                }

                return [
                    'name' => Status::tryFrom($status)?->label(),
                    'variant' => $variant,
                ];
            },
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
