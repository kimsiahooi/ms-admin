<?php

namespace App\enums\Tenant\Product\Preset;

enum CavityType: int
{
    case PCS = 1;
    case GRAM = 2;
    case KILOGRAM = 3;

    public function label(): string
    {
        return match ($this) {
            self::PCS => 'Pcs',
            self::GRAM => 'Gram',
            self::KILOGRAM => 'Kilogram',
        };
    }
}
