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

export interface Material {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    unit_type: number;
    unit_type_label?: 'Pcs' | 'Kilogram' | 'Gram' | null;
    status: Status;
    status_badge?: {
        name: StatusBadgeLabel | null;
        variant: BadgeVariants['variant'];
    } | null;
    status_switch?: boolean | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
