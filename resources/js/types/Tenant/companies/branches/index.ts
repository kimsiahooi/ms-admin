import type { BadgeVariants } from '@/components/shared/badge';
import type { Company } from '@/types/Tenant/companies';

export type StatusBadgeLabel = 'Active' | 'Inactive';

export interface CompanyBranch {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    address: string;
    status: 'ACTIVE' | 'INACTIVE';
    status_badge?: {
        name: StatusBadgeLabel | null;
        variant: BadgeVariants['variant'];
    } | null;
    company_id: Company['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
