export interface TenantUser {
    readonly id: string;
    name: string;
    email: string;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
