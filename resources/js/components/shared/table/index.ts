export { default as DataTable } from './DataTable.vue';
export { default as Dropdown } from './Dropdown.vue';

import type { Method } from '@inertiajs/core';
import type { ColumnDef } from '@tanstack/vue-table';

export type Columns<TData, TValue> = ColumnDef<TData, TValue>[];

export interface DataTableProp<TData, TValue> {
    columns: Columns<TData, TValue>;
    data: TData[];
}

export interface DropdownLink {
    name: string;
    type: 'link';
    url: string;
    method?: Method;
}

export interface DropdownHandler {
    name: string;
    type: 'button';
    onClick: () => void;
}

export type DropdownAction = DropdownLink | DropdownHandler;

export type VisibilityState<T extends object = object> = Record<keyof T, boolean>;
