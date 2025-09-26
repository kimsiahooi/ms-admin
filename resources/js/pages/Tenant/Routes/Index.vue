<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { DeleteDialog } from '@/components/shared/dialog';
import { PaginateData } from '@/components/shared/pagination';
import type { SelectOption } from '@/components/shared/select';
import { StatusSwitch } from '@/components/shared/switch';
import type { VisibilityState } from '@/components/shared/table';
import { DataTable } from '@/components/shared/table';
import { Button } from '@/components/ui/button';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Filter } from '@/types/shared';
import { Route } from '@/types/Tenant/routes';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, h } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

defineProps<{
    routes: PaginateData<Route[]>;
    options: {
        select: {
            statuses: SelectOption<Route['status']['value']>[];
        };
    };
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
        title: 'Routes',
        href: route('routes.index', { tenant: tenant?.id || '' }),
    },
]);

const search = () =>
    router.visit(route('routes.index', { ...pickBy(filter.data()), tenant: tenant?.id || '' }), {
        preserveScroll: true,
        preserveState: true,
    });
const reset = () => {
    filter.reset();
    search();
};

const columns: ColumnDef<Route>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const routing = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(StatusSwitch, {
                    value: routing.status.switch,
                    method: 'put',
                    href: route('routes.toggleStatus', { tenant: tenant?.id || '', route: routing.id }),
                }),
                h(ActionButton, {
                    text: 'Edit',
                    href: route('routes.edit', { tenant: tenant?.id || '', route: routing.id }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${routing.name}`,
                        route: route('routes.destroy', { tenant: tenant?.id || '', route: routing.id }),
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
            const { status } = row.original;

            return h(StatusBadge, { statusBadge: status.badge });
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

const columnVisibility: VisibilityState<Route> = {
    id: false,
    description: false,
};
</script>

<template>
    <Head title="Routes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <FilterCard @search="search" @reset="reset">
                    <FilterInput label="Name" placeholder="Search ID, Name, Code" v-model:model-value="filter.search" />
                    <FilterSelect
                        label="Status"
                        placeholder="Select Status"
                        :options="options.select.statuses"
                        multiple
                        v-model:model-value="filter.status"
                    />
                </FilterCard>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Link :href="route('routes.create', { tenant: tenant?.id || '' })" as-child>
                        <Button class="cursor-pointer">Create</Button>
                    </Link>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="routes"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
