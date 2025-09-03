import type { BadgeVariants } from '@/components/shared/badge';

export type StatusLabel = 'Active' | 'Inactive';

export interface Product {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    status: 'ACTIVE' | 'INACTIVE';
    status_label?: {
        name: StatusLabel | null;
        variant: BadgeVariants['variant'];
    };
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
