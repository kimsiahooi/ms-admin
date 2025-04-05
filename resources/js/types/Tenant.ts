export interface Tenant {
    readonly id: string;
    name: string;
    tenancy_db_name: string;
    created_at: Date;
    updated_at: Date;
}
