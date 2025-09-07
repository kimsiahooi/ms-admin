<?php

namespace App\Enums\Tenant\Product\Preset;

enum ShelfLifeType: string
{
    case MILLISECOND = 'MILLISECOND';
    case SECOND = 'SECOND';
    case MINUTE = 'MINUTE';
    case HOUR = 'HOUR';
    case DAY = 'DAY';
    case MONTH = 'MONTH';
    case YEAR = 'YEAR';

    public function label(): string
    {
        return match ($this) {
            self::MILLISECOND => 'Millisecond(s)',
            self::SECOND => 'Second(s)',
            self::MINUTE => 'Minute(s)',
            self::HOUR => 'Hour(s)',
            self::DAY => 'Day(s)',
            self::MONTH => 'Month(s)',
            self::YEAR => 'Year(s)',
        };
    }

    public static function selectOptions(): array
    {
        return collect(ShelfLifeType::cases())
            ->map(fn(ShelfLifeType $type) => [
                'name' => $type->label(),
                'value' => $type->value,
            ])->toArray();
    }
}
