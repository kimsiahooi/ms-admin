<?php

namespace App\enums\Tenant\Product\Preset;

enum ShelfLifeType: int
{
    case SECOND = 1;
    case MINUTE = 2;
    case HOUR = 3;
    case DAY = 4;
    case MONTH = 5;
    case YEAR = 6;

    public function label(): string
    {
        return match ($this) {
            self::SECOND => 'Second(s)',
            self::MINUTE => 'Minute(s)',
            self::HOUR => 'Hour(s)',
            self::DAY => 'Day(s)',
            self::MONTH => 'Month(s)',
            self::YEAR => 'Year(s)',
        };
    }
}
