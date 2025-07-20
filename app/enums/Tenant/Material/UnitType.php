<?php

namespace App\enums\Tenant\Material;

enum UnitType: string
{
    case PCS = 'PCS';
    case KILOGRAM = 'KILOGRAM';
    case GRAM = 'GRAM';

    public function display(): string
    {
        return match ($this) {
            self::PCS => 'Pcs',
            self::KILOGRAM => 'Kilogram',
            self::GRAM => 'Gram',
        };
    }
}
