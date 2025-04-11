import type { User } from '@/types/User';

export interface Audit<T extends object> {
    readonly id: number;
    log_name: string;
    description: string;
    subject_type: string;
    event: string;
    subject_id: number;
    causer_type: string;
    causer_id: number | null;
    properties: {
        attributes?: Partial<T>;
        old?: Partial<T>;
    };
    batch_uuid: string | null;
    created_at: Date;
    updated_at: Date;
    causer?: User | null;
}
