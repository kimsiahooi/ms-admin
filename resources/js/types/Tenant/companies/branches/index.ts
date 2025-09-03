import type { BadgeVariants } from '@/components/shared/badge';
import type { Company } from '@/types/Tenant/companies';

export type StatusLabel = 'Active' | 'Inactive';

export interface CompanyBranch {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    address: string;
    status: 'ACTIVE' | 'INACTIVE';
    status_label?: {
        name: StatusLabel | null;
        variant: BadgeVariants['variant'];
    };
    company_id: Company['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
