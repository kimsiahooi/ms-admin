<script setup lang="ts">
import { DeleteDialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination/types';
import { DataTable } from '@/components/shared/table';
import type { Filter, SearchConfig, VisibilityState } from '@/components/shared/table/types';
import { Tooltip } from '@/components/shared/tooltip';
import { Button } from '@/components/ui/button';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Admin/AppLayout.vue';
import AppMainLayout from '@/layouts/Admin/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Tenant } from '@/types/Admin/tenants';
import { Head, Link, router } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { Trash2 } from 'lucide-vue-next';
import { computed, h, reactive } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

defineProps<{
    tenants: PaginateData<Tenant[]>;
}>();

const routeParams = computed(() => route().params);

const { formatDateTime } = useFormatDateTime();

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
        href: route('admin.dashboard'),
    },
    {
        title: 'Tenants',
        href: route('admin.tenants.index'),
    },
];

const filterChangeHandler = (filter: Filter) => {
    router.visit(route('admin.tenants.index', { ...filter }));
};

const columnVisibility = <VisibilityState<Partial<Tenant>>>{};

const columns: ColumnDef<Tenant>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const tenant = row.original;

            return h(
                'div',
                { class: 'flex items-center gap-2' },
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${tenant.name}`,
                        route: route('admin.tenants.destroy', { tenant: tenant.id }),
                        asChild: false,
                    },
                    () =>
                        h(Tooltip, { text: 'Delete' }, () =>
                            h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full', variant: 'destructive' }, () =>
                                h(Trash2, { class: 'size-3' }),
                            ),
                        ),
                ),
            );
        },
    },
    {
        accessorKey: 'id',
        header: () => h('div', null, 'ID'),
        cell: ({ row }) => {
            const { id } = row.original;

            return h(
                'div',
                null,
                h(Link, { href: route('dashboard', { tenant: id }), asChild: true }, () =>
                    h(Button, { variant: 'link', class: 'cursor-pointer' }, () => row.getValue('id')),
                ),
            );
        },
    },
    {
        accessorKey: 'name',
        header: () => h('div', null, 'Name'),
        cell: ({ row }) => h('div', null, row.getValue('name')),
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
    <Head title="Tenants" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Link :href="route('admin.tenants.create')" as-child>
                        <Button class="cursor-pointer">Create</Button>
                    </Link>
                </div>
                <div>
                    <DataTable
                        :columns="columns"
                        :paginate-data="tenants"
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
