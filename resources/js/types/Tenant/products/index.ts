import type { Material } from '@/types/Tenant/materials';

export interface Product {
    readonly id: number;
    name: string;
    code: string;
    description: string | null;
    unit_price: string;
    is_active: boolean;
    is_active_display?: string | null;
    material_id: number;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductWithMaterial extends Product {
    material?: Material;
}
