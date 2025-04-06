export type RolePermission = 'View Role' | 'Create Role' | 'Update Role' | 'Delete Role';

export interface Permission {
    readonly id: number;
    name: RolePermission;
    guard_name: string;
    created_at: Date;
    updated_at: Date;
}
