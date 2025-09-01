export interface Material {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    unit_type: number;
    unit_type_label?: 'Pcs' | 'Kilogram' | 'Gram' | null;
    status: 'ACTIVE' | 'INACTIVE';
    status_label?: 'Active' | 'Inactive' | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
