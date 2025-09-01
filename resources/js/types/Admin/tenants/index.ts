export interface Tenant {
    readonly id: string;
    name: string;
    status: number;
    status_label?: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    data: string | null;
}
