import type { BadgeVariants } from '@/components/shared/badge';
import type { Company } from '@/types/Tenant/companies';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export interface CompanyBranch {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    address: string;
    status: {
        value: Status;
        badge?: {
            name: StatusBadgeLabel | null;
            variant: BadgeVariants['variant'];
        } | null;
        switch?: boolean | null;
    };
    company_id: Company['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
