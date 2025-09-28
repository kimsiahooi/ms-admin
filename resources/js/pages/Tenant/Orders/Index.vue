<script setup lang="ts">
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput } from '@/components/shared/custom/filter';
import { DeleteDialog } from '@/components/shared/dialog';
import { PaginateData } from '@/components/shared/pagination';
import type { VisibilityState } from '@/components/shared/table';
import { DataTable } from '@/components/shared/table';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Filter } from '@/types/shared';
import { Order } from '@/types/Tenant/orders';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, h } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

defineProps<{
    orders: PaginateData<Order[]>;
}>();

const { tenant } = useTenant();
const { formatDateTime } = useFormatDateTime();

const routeParams = computed(() => route().params);

const filter = useForm<Filter>({
    search: routeParams.value.search,
    entries: routeParams.value.entries || '10',
    status: routeParams.value.status,
});

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Orders',
        href: route('orders.index', { tenant: tenant?.id || '' }),
    },
]);

const search = () =>
    router.visit(route('orders.index', { ...pickBy(filter.data()), tenant: tenant?.id || '' }), {
        preserveScroll: true,
        preserveState: true,
    });
const reset = () => {
    filter.reset();
    search();
};

const columns: ColumnDef<Order>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const order = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(ActionButton, {
                    text: 'Edit',
                    href: route('orders.edit', { tenant: tenant?.id || '', order: order.id }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete Order ID: ${order.id}`,
                        route: route('orders.destroy', { tenant: tenant?.id || '', order: order.id }),
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
        accessorKey: 'id',
        header: () => h('div', null, 'Id'),
        cell: ({ row }) => h('div', null, row.getValue('id')),
    },
    {
        accessorKey: 'code',
        header: () => h('div', null, 'Code'),
        cell: ({ row }) => h('div', null, row.getValue('code')),
    },
    {
        accessorKey: 'currency',
        header: () => h('div', null, 'Currency'),
        cell: ({ row }) => h('div', null, row.getValue('currency')),
    },
    {
        accessorKey: 'remark',
        header: () => h('div', null, 'Remark'),
        cell: ({ row }) => h('div', null, row.getValue('remark')),
    },
    {
        accessorKey: 'delivery_date',
        header: () => h('div', null, 'Delivery Date'),
        cell: ({ row }) => h('div', null, formatDateTime(row.getValue('delivery_date')) || ''),
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

const columnVisibility: VisibilityState<Order> = {
    id: false,
    remark: false,
};
</script>

<template>
    <Head title="Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <FilterCard @search="search" @reset="reset">
                    <FilterInput label="Name" placeholder="Search ID, Code" v-model:model-value="filter.search" />
                </FilterCard>

                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="orders"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
