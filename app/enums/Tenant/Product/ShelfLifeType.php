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
            self::SECOND => 'Second',
            self::MINUTE => 'Minute',
            self::HOUR => 'Hour',
            self::DAY => 'Day',
            self::MONTH => 'Month',
            self::YEAR => 'Year',
        };
    }
}
