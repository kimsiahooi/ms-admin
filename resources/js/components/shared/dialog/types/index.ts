export interface DeleteDialogType<T = null> {
    isOpen: boolean;
    isDeleting: boolean;
    title: string;
    data: T | null;
}
