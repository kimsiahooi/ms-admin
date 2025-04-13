export type RolePermission = 'View Role' | 'Create Role' | 'Edit Role' | 'Force Delete Role' | 'Audit Role';
export type UserPermission =
    | 'View User'
    | 'Create User'
    | 'Edit User'
    | 'Delete User'
    | 'View Trashed User'
    | 'Restore User'
    | 'Force Delete User'
    | 'Audit User';
export type TenantPermission = 'View Tenant' | 'Create Tenant' | 'Edit Tenant' | 'Force Delete Tenant';

export type AllPermission = RolePermission | UserPermission | TenantPermission;

export interface Permission {
    readonly id: number;
    name: AllPermission;
    guard_name: string;
    created_at: Date;
    updated_at: Date;
}
