import type { BadgeVariants } from '@/components/shared/badge';
import type { Material } from '@/types/Tenant/materials';
import type { Product } from '@/types/Tenant/products';

export type StatusBadgeLabel = 'Active' | 'Inactive';

export interface ProductBom {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    status: 'ACTIVE' | 'INACTIVE';
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
