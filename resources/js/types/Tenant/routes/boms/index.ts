import { BadgeVariants } from '@/components/shared/badge';
import { ProductBom } from '@/types/Tenant/products/boms';
import { Route } from '@/types/Tenant/routes';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export interface PivotProductBom extends ProductBom {
    pivot: {
        readonly id: string;
        route_id: Route['id'];
        bom_id: ProductBom['id'];
        status: {
            value: Status;
            badge?: {
                name: StatusBadgeLabel | null;
                variant: BadgeVariants['variant'];
            } | null;
            switch?: boolean | null;
        };
        created_at: Date | null;
        updated_at: Date | null;
    };
}
