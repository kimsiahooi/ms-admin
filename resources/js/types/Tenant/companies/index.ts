export interface Company {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    status: number;
    status_label?: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface CompanyBranch {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    address: string;
    status: number;
    status_label?: string | null;
    company_id: Company['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
