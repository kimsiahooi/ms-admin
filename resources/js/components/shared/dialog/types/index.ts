export type DialogMethodType = 'post' | 'put' | 'delete';

export interface DialogType<T = null> {
    method: DialogMethodType | null;
    isOpen: boolean;
    title: string;
    data: T | null;
}
