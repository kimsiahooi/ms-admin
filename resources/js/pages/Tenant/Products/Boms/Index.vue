<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { DeleteDialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { DataTable } from '@/components/shared/table';
import type { VisibilityState } from '@/components/shared/table/types';
import { Button } from '@/components/ui/button';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Product } from '@/types/Tenant/products';
import type { ProductBom, StatusLabel } from '@/types/Tenant/products/boms';
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
    options: {
        statuses: SwitchOption<ProductBom['status'], StatusLabel>[];
    };
}>();

const { tenant } = useTenant();
const { formatDateTime } = useFormatDateTime();

const routeParams = computed(() => route().params);

const filter = reactive<Record<string, string>>({
    search: routeParams.value.search,
    entries: routeParams.value.entries || '10',
    status: routeParams.value.status,
});

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

const search = () => router.visit(route('products.boms.index', { ...pickBy(filter), tenant: tenant?.id || '', product: props.product.id }));
const reset = () => router.visit(route('products.boms.index', { tenant: tenant?.id || '', product: props.product.id }));

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
                h(ActionButton, {
                    text: 'Edit',
                    href: route('products.boms.edit', { tenant: tenant?.id || '', product: props.product.id, bom: bom.id }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${bom.name}`,
                        route: route('products.boms.destroy', { tenant: tenant?.id || '', product: props.product.id, bom: bom.id }),
                        asChild: false,
                    },
                    () =>
                        h(ActionButton, {
                            variant: 'destructive',
                            text: 'Delete',
                            icon: Trash2,
                        }),
                ),
            ]);
        },
    },
    {
        accessorKey: 'status',
        header: () => h('div', null, 'Status'),
        cell: ({ row }) => {
            const { status_label } = row.original;

            return h(StatusBadge, { statusLabel: status_label });
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
    {
        accessorKey: 'created_at',
        header: () => h('div', null, 'Created At'),
        cell: ({ row }) => h('div', null, formatDateTime(row.getValue('created_at')) || ''),
    },
    {
        accessorKey: 'updated_at',
        header: () => h('div', null, 'Updated At'),
        cell: ({ row }) => h('div', null, formatDateTime(row.getValue('updated_at')) || ''),
    },
];
</script>

<template>
    <Head title="Product Boms" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <FilterCard @search="search" @reset="reset">
                    <FilterInput label="Name" placeholder="Search ID, Name, Code" v-model:model-value="filter.search" />
                    <FilterSelect
                        label="Status"
                        placeholder="Select Status"
                        :options="options.statuses"
                        multiple
                        v-model:model-value="filter.status"
                    />
                </FilterCard>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Link :href="route('products.boms.create', { tenant: tenant?.id || '', product: props.product.id })" as-child>
                        <Button class="cursor-pointer">Create</Button>
                    </Link>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="boms"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
