<?php

namespace App\Enums\Tenant\Product\Bom;

enum Status: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }

    public function variant(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'destructive',
            default => 'default',
        };
    }

    public function badge(): array
    {
        return [
            'name' => $this?->label(),
            'variant' => $this?->variant(),
        ];
    }

    public function defaultValue(): bool
    {
        return match ($this) {
            self::ACTIVE => true,
            default => false,
        };
    }

    public function switch(): bool
    {
        return $this?->defaultValue();
    }

    public static function options(): array
    {
        return collect(Status::cases())
            ->map(fn(Status $status) => [
                'name' => $status->label(),
                'value' => $status->value,
                'is_default' => $status->defaultValue(),
            ])->toArray();
    }

    public static function switchOptions(): array
    {
        return collect(Status::cases())
            ->map(fn(Status $status) => [
                'name' => $status->label(),
                'value' => $status->defaultValue(),
                'is_default' => $status->defaultValue(),
            ])->toArray();
    }

    public static function toggleStatus(bool $value): string
    {
        return match ($value) {
            true => Status::ACTIVE->value,
            default => Status::INACTIVE->value,
        };
    }
}
