export interface SwitchOption<T = string> {
    name: string;
    value: T;
    is_default?: boolean;
}
