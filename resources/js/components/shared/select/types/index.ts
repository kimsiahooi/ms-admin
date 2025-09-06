import type { AcceptableValue } from 'reka-ui';

export interface SelectOption<T extends AcceptableValue = AcceptableValue> {
    name: string;
    value: T;
}
