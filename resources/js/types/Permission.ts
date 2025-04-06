export type RolePermission = 'View Role' | 'Create Role' | 'Update Role' | 'Delete Role';
export type UserPermission = 'View User' | 'Create User' | 'Update User' | 'Delete User' | 'Restore User' | 'Force Delete User';

export type AllPermission = RolePermission | UserPermission;

export interface Permission {
    readonly id: number;
    name: AllPermission;
    guard_name: string;
    created_at: Date;
    updated_at: Date;
}
