export interface Tenant {
    readonly id: number;
    name: string;
    tenancy_db_name: string;
    created_at: Date;
    updated_at: Date;
}
