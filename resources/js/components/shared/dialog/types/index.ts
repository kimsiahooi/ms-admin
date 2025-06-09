export type DialogMethodType<T extends string = 'store' | 'update' | 'destroy'> = T;

export interface DialogType<T = null> {
    type: DialogMethodType | null;
    isOpen: boolean;
    title: string;
    data: T | null;
}
