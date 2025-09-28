import type { BadgeVariants } from '@/components/shared/badge';
import { Operation } from '@/types/Tenant/plants/departments/operations';
import { ProductBom } from '@/types/Tenant/products/boms';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export interface Route {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
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
    deleted_at: Date | null;
}

export interface RouteWithOperations extends Route {
    operations: (Operation & {
        pivot: {
            readonly id: string;
            route_id: Route['id'];
            operation_id: Operation['id'];
            created_at: Date | null;
            updated_at: Date | null;
        };
    })[];
}

export interface RouteWithBoms extends Route {
    boms: (ProductBom & {
        pivot: {
            readonly id: string;
            bom_id: ProductBom['id'];
            route_id: Route['id'];
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
    })[];
}
