export type DialogMethodType<T extends string = 'store' | 'update' | 'destroy'> = T;

export interface DialogType<T = null, U extends string = DialogMethodType> {
    type: U | null;
    isOpen: boolean;
    title: string;
    data: T | null;
}
