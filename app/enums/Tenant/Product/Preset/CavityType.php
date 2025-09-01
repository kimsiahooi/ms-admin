<?php

namespace App\enums\Tenant\Product\Preset;

enum CavityType: int
{
    case PCS = 'PCS';
    case GRAM = 'GRAM';
    case KILOGRAM = 'KILOGRAM';

    public function label(): string
    {
        return match ($this) {
            self::PCS => 'Pcs',
            self::GRAM => 'Gram',
            self::KILOGRAM => 'Kilogram',
        };
    }
}
