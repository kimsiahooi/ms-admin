import type { BadgeVariants } from '@/components/shared/badge';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export enum UnitType {
    PCS = 'PCS',
    KILOGRAM = 'KILOGRAM',
    GRAM = 'GRAM',
}

export const UnitTypeLabel: Record<UnitType, string> = {
    [UnitType.PCS]: 'Pcs',
    [UnitType.KILOGRAM]: 'Kilogram',
    [UnitType.GRAM]: 'Gram',
};

export interface Material {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    unit_type: UnitType;
    unit_type_label?: (typeof UnitTypeLabel)[UnitType] | null;
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
