export type UserRole = 'Super Admin' | 'Admin' | 'User';

export type AllRole = UserRole;

export interface Role {
    readonly id: number;
    name: AllRole;
    guard_name: string;
    created_at: Date;
    updated_at: Date;
}
