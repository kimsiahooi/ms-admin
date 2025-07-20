<?php

namespace App\enums\Tenant\Product;

enum ShelfLifeType: string
{
    case SECOND = 'second';
    case MINUTE = 'minute';
    case HOUR = 'hour';
    case DAY = 'day';
    case MONTH = 'month';
    case YEAR = 'year';

    public function display(): string
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
