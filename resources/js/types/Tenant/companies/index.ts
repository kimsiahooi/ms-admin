import type { BadgeVariants } from '@/components/ui/badge';

export type StatusBadgeLabel = 'Active' | 'Inactive';

export interface Company {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    status: 'ACTIVE' | 'INACTIVE';
    status_badge?: {
        name: StatusBadgeLabel | null;
        variant: BadgeVariants['variant'];
    } | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
