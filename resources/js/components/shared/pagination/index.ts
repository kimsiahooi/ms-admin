export { default as Pagination } from './Pagination.vue';
export { default as PaginationButton } from './PaginationButton.vue';

export interface PaginateLink {
    active: boolean;
    label: string;
    url: string | null;
}

export interface PaginateData<T> {
    current_page: number;
    data: T;
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: PaginateLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
}
