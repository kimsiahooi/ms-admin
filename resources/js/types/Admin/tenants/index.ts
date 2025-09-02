export type StatusLabel = 'Active' | 'Inactive';

export interface Tenant {
    readonly id: string;
    name: string;
    status: 'ACTIVE' | 'INACTIVE';
    status_label?: StatusLabel | null;
    created_at: Date | null;
    updated_at: Date | null;
    data: string | null;
}
