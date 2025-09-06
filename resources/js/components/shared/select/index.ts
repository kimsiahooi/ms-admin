export { default as MultiSelect } from './MultiSelect.vue';
export { default as Select } from './Select.vue';
import type { AcceptableValue } from 'reka-ui';

export interface SelectOption<T extends AcceptableValue = AcceptableValue> {
    name: string;
    value: T;
}
