import type { BadgeVariants } from '@/components/shared/badge';
import type { Product } from '@/types/Tenant/products';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export interface ProductPreset {
    readonly id: string;
    product_id: string;
    name: string;
    code: string;
    description: string | null;
    quantity: string;
    product_type: number;
    cavity_type_label?: string | null;
    cycle_time: string;
    cycle_time_type: number;
    cycle_time_type_label?: string | null;
    shelf_life_duration: string | null;
    shelf_life_type: number | null;
    shelf_life_type_label?: string | null;
    status: {
        value: Status;
        badge?: {
            name: StatusBadgeLabel | null;
            variant: BadgeVariants['variant'];
        } | null;
        switch?: boolean | null;
    };
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductPresetWithProduct extends ProductPreset {
    product: Product | null;
}
