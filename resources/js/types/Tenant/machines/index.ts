export type StatusLabel = 'Active' | 'Inactive';

export interface Machine {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    status: 'ACTIVE' | 'INACTIVE';
    status_label?: StatusLabel | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
