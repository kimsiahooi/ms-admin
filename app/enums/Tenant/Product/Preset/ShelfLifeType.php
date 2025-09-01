<?php

namespace App\enums\Tenant\Product\Preset;

enum ShelfLifeType: int
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
}
