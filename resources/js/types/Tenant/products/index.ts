import type { Material } from '../materials';

export interface Product {
    readonly id: number;
    name: string;
    code: string;
    description: string | null;
    unit_price: string;
    is_active: boolean;
    is_active_display?: string | null;
    shelf_life_days: number | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductWithMaterials extends Product {
    materials: Material[];
}
