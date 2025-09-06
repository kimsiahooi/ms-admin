import type { BadgeVariants } from '@/components/shared/badge';
import type { Machine } from '@/types/Tenant/machines';
import type { Product } from '@/types/Tenant/products';

export type StatusBadgeLabel = 'Active' | 'Inactive';

export interface ProductPreset {
    readonly id: string;
    product_id: string;
    machine_id: string;
    name: string;
    code: string;
    description: string | null;
    cavity_quantity: string;
    cavity_type: number;
    cavity_type_label?: string | null;
    cycle_time: string;
    cycle_time_type: number;
    cycle_time_type_label?: string | null;
    shelf_life_duration: string | null;
    shelf_life_type: number | null;
    shelf_life_type_label?: string | null;
    status: 'ACTIVE' | 'INACTIVE';
    status_badge?: {
        name: StatusBadgeLabel | null;
        variant: BadgeVariants['variant'];
    } | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductPresetWithProduct extends ProductPreset {
    product: Product | null;
}

export interface ProductPresetWithMachine extends ProductPreset {
    machine: Machine | null;
}

export interface ProductPresetWithProductAndMachine extends ProductPreset {
    product: Product | null;
    machine: Machine | null;
}
