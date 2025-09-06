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

export interface TenantUserWithPlants extends TenantUser {
    plants: (Plant & {
        pivot: {
            id: string;
            plant_id: Plant['id'];
            tenant_user_id: TenantUser['id'];
            created_at: Date | null;
            updated_at: Date | null;
        };
    })[];
}
