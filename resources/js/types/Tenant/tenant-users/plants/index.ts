import type { Plant } from '@/types/Tenant/plants';
import type { TenantUser } from '@/types/Tenant/tenant-users';

export type StatusBadgeLabel = 'Active' | 'Inactive';

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
