<?php

namespace App\Enums\Tenant\Product\Preset;

enum ProductType: string
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

    public static function selectOptions(): array
    {
        return collect(ProductType::cases())
            ->map(fn(ProductType $type) => [
                'name' => $type->label(),
                'value' => $type->value,
            ])->toArray();
    }
}
