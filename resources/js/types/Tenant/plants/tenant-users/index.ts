import type { Plant } from '@/types/Tenant/plants';
import type { TenantUser } from '@/types/Tenant/tenant-users';

export type StatusBadgeLabel = 'Active' | 'Inactive';

export interface PlantWithTenantUsers extends Plant {
    tenant_users: (TenantUser & {
        pivot: {
            id: string;
            created_at: Date | null;
            updated_at: Date | null;
        };
    })[];
}
