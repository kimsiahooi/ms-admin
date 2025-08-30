<?php

namespace App\enums\Tenant\Product\Preset;

enum CycleTimeType: int
{
    case MILLISECOND = 1;
    case SECOND = 2;
    case MINUTE = 3;
    case HOUR = 4;
    case DAY = 5;
    case MONTH = 6;
    case YEAR = 7;

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
}
