import type { AcceptableValue } from 'reka-ui';

export interface SwitchOption<T extends AcceptableValue = AcceptableValue, U extends AcceptableValue = AcceptableValue> {
    name: U;
    value: T;
    is_default?: boolean;
}
