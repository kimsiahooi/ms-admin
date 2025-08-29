<script setup lang="ts">
import { DeleteDialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination/types';
import { DataTable } from '@/components/shared/table';
import type { Filter, SearchConfig, VisibilityState } from '@/components/shared/table/types';
import { Tooltip } from '@/components/shared/tooltip';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Product, ProductBom } from '@/types/Tenant/products';
import { Head, Link, router } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, h, reactive } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    boms: PaginateData<ProductBom[]>;
}>();

const { tenant } = useTenant();

const routeParams = computed(() => route().params);

const filter = reactive<Filter>({
    search: routeParams.value.search,
    entries: routeParams.value.entries || '10',
});

const searchConfig: SearchConfig = {
    placeholder: 'Search name...',
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Products',
        href: route('products.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.product.name,
        href: '#',
    },
    {
        title: 'Boms',
        href: route('products.boms.index', { tenant: tenant?.id || '', product: props.product.id }),
    },
];

const filterChangeHandler = (filter: Filter) => {
    router.visit(route('products.boms.index', { ...pickBy(filter), tenant: tenant?.id || '', product: props.product.id }));
};

const columnVisibility = <VisibilityState<Partial<ProductBom>>>{
    id: false,
    description: false,
};

const columns: ColumnDef<ProductBom>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const bom = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Tooltip, { text: 'Edit' }, () =>
                    h(Link, { href: route('products.boms.edit', { tenant: tenant?.id || '', product: props.product.id, bom: bom.id }) }, () =>
                        h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full' }, () => h(Pencil, { class: 'size-3' })),
                    ),
                ),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${bom.name}`,
                        route: route('products.boms.destroy', { tenant: tenant?.id || '', product: props.product.id, bom: bom.id }),
                        asChild: false,
                    },
                    () =>
                        h(Tooltip, { text: 'Delete' }, () =>
                            h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full', variant: 'destructive' }, () =>
                                h(Trash2, { class: 'size-3' }),
                            ),
                        ),
                ),
            ]);
        },
    },
    {
        accessorKey: 'is_active',
        header: () => h('div', null, 'Active'),
        cell: ({ row }) => {
            const { is_active, is_active_display } = row.original;

            return h(Badge, { variant: is_active ? 'default' : 'destructive' }, () => is_active_display);
        },
    },
    {
        accessorKey: 'id',
        header: () => h('div', null, 'Id'),
        cell: ({ row }) => h('div', null, row.getValue('id')),
    },
    {
        accessorKey: 'name',
        header: () => h('div', null, 'Name'),
        cell: ({ row }) => h('div', null, row.getValue('name')),
    },
    {
        accessorKey: 'code',
        header: () => h('div', null, 'Code'),
        cell: ({ row }) => h('div', null, row.getValue('code')),
    },
    {
        accessorKey: 'description',
        header: () => h('div', null, 'Description'),
        cell: ({ row }) => h('div', null, row.getValue('description')),
    },
];
</script>

<template>
    <Head title="Product Boms" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Link :href="route('products.boms.create', { tenant: tenant?.id || '', product: props.product.id })" as-child>
                        <Button class="cursor-pointer">Create</Button>
                    </Link>
                </div>
                <div>
                    <DataTable
                        :columns="columns"
                        :paginate-data="boms"
                        :column-visibility="columnVisibility"
                        :filter="filter"
                        :entry-options="entryOptions"
                        :search-config="searchConfig"
                        @filter-change="filterChangeHandler"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
