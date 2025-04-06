export interface User {
    readonly id: number;
    name: string;
    email: string;
    email_verified_at: Date | null;
    created_at: Date;
    updated_at: Date;
    deleted_at: Date | null;
}
