import type { Material } from '@/types/Tenant/materials';

export { default as MaterialSelect } from './MaterialSelect.vue';

export interface MaterialConfig {
    key: string;
    data: Material | null;
    quantity: number | '';
    unit_type: Material['unit_type'] | '';
}
