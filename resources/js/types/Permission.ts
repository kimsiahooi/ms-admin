export type RolePermission = 'View Role' | 'Create Role' | 'Update Role' | 'Delete Role';

export type AllPermission = RolePermission;

export interface Permission {
    readonly id: number;
    name: AllPermission;
    guard_name: string;
    created_at: Date;
    updated_at: Date;
}
