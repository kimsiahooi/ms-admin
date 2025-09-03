import type { BadgeVariants } from '@/components/shared/badge';

export type StatusLabel = 'Active' | 'Inactive';

export interface Tenant {
    readonly id: string;
    name: string;
    status: 'ACTIVE' | 'INACTIVE';
    status_label?: {
        name: StatusLabel | null;
        variant: BadgeVariants['variant'];
    };
    created_at: Date | null;
    updated_at: Date | null;
    data: string | null;
}
