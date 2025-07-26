export interface Machine {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    is_active: boolean;
    is_active_display?: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
