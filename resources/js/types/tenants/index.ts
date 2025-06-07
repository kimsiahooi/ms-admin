export interface Tenant {
    readonly id: string;
    created_at: Date | null;
    updated_at: Date | null;
    data: string | null;
}
