export { default as StatusSwitch } from './StatusSwitch.vue';

export interface SwitchOption {
    name: string;
    value: boolean;
    is_default?: boolean;
}
