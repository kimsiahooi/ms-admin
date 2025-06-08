export interface Tenant {
    readonly id: string;
    name: string;
    created_at: Date | null;
    updated_at: Date | null;
    data: string | null;
}
