import type { Product } from '..';

export interface Bom {
    readonly id: number;
    name: string;
    code: string;
    description: string | null;
    is_active: boolean;
    is_active_display?: string | null;
    product_id: number;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface BomWithProduct extends Bom {
    product: Product;
}
