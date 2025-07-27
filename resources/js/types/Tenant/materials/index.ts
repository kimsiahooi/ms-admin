export interface Material {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    unit_type: 'PCS' | 'KILOGRAM' | 'GRAM';
    unit_type_display?: 'Pcs' | 'Kilogram' | 'Gram' | null;
    is_active: boolean;
    is_active_display?: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
