import type { BadgeVariants } from '@/components/shared/badge';

export type StatusBadgeLabel = 'Active' | 'Inactive';

export interface Material {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    unit_type: number;
    unit_type_label?: 'Pcs' | 'Kilogram' | 'Gram' | null;
    status: 'ACTIVE' | 'INACTIVE';
    status_badge?: {
        name: StatusBadgeLabel | null;
        variant: BadgeVariants['variant'];
    } | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
