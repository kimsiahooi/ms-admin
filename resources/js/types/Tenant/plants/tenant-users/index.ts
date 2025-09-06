import type { Plant } from '@/types/Tenant/plants';
import type { TenantUser } from '@/types/Tenant/tenant-users';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export interface PlantWithTenantUsers extends Plant {
    tenant_users: (TenantUser & {
        pivot: {
            id: string;
            created_at: Date | null;
            updated_at: Date | null;
        };
    })[];
}
