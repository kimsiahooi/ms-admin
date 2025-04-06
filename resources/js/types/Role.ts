export type UserRole = 'Super Admin' | 'Admin' | 'User';

export interface Role {
    readonly id: number;
    name: UserRole;
    guard_name: string;
    created_at: Date;
    updated_at: Date;
}
