import { CustomerBranch } from '@/types/Tenant/customers/branches';
import { Currency } from '@/types/Tenant/products/prices';

export interface Order {
    readonly id: string;
    code: string;
    currency: Currency;
    remark: string | null;
    delivery_date: Date;
    customer_branch_id: CustomerBranch['id'] | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}
