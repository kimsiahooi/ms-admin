import type { AcceptableValue } from 'reka-ui';

export interface SelectOption<T = AcceptableValue> {
    name: string;
    value: T;
}
