import type { Material } from '@/types/Tenant/materials';

export { default as MaterialSelect } from './MaterialSelect.vue';

export interface MaterialConfig<T = Material> {
    key: string;
    data: T | null;
    quantity: number | '';
    unit_type: Material['unit_type'] | '';
}
