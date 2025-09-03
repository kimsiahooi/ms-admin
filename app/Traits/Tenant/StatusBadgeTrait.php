<?php

namespace App\Traits\Tenant;

trait StatusBadgeTrait
{
    public function formatStatus(string $status, string $enumClass)
    {
        $enum = $enumClass::tryFrom($status);

        return [
            'name' => $enum?->label(),
            'variant' => $enum?->variant(),
        ];
    }
}
