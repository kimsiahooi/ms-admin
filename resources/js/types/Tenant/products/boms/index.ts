import type { BadgeVariants } from '@/components/shared/badge';
import type { Material } from '@/types/Tenant/materials';
import type { Product } from '@/types/Tenant/products';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export interface ProductBom {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    status: Status;
    status_badge?: {
        name: StatusBadgeLabel | null;
        variant: BadgeVariants['variant'];
    } | null;
    product_id: Product['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductBomWithProduct extends ProductBom {
    product: Product | null;
}

export interface ProductBomWithMaterials extends ProductBom {
    materials: (Material & {
        pivot: {
            id: string;
            bom_id: ProductBom['id'];
            material_id: Material['id'];
            quantity: string;
            unit_type: Material['unit_type'];
            created_at: Date | null;
            updated_at: Date | null;
        };
    })[];
}
