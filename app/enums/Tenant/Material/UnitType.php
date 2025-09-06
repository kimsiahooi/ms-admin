<?php

namespace App\enums\Tenant\Material;

enum UnitType: string
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

    public static function options(): array
    {
        return collect(UnitType::cases())
            ->map(fn(UnitType $type) => [
                'name' => $type->label(),
                'value' => $type->value,
            ])->toArray();
    }
}
