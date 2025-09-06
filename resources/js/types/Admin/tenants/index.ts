import type { BadgeVariants } from '@/components/shared/badge';

export type StatusBadgeLabel = 'Active' | 'Inactive';

export interface Tenant {
    readonly id: string;
    name: string;
    status: 'ACTIVE' | 'INACTIVE';
    status_badge?: {
        name: StatusBadgeLabel | null;
        variant: BadgeVariants['variant'];
    } | null;
    created_at: Date | null;
    updated_at: Date | null;
    data: string | null;
}
