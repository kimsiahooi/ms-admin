<?php

namespace App\enums\Tenant\Material;

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

    public function switch(): bool
    {
        return match ($this) {
            self::ACTIVE => true,
            default => false,
        };
    }

    public static function options(): array
    {
        return collect(Status::cases())
            ->map(fn(Status $status) => [
                'name' => $status->label(),
                'value' => $status->value,
                'is_default' => $status->value === Status::ACTIVE->value,
            ])->toArray();
    }
}
