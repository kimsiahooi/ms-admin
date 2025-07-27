<script setup lang="ts">
import type { PaginateData } from '@/components/shared/pagination/types';
import { DataTable } from '@/components/shared/table';
import type { Filter, SearchConfig, VisibilityState } from '@/components/shared/table/types';
import { Button } from '@/components/ui/button';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Admin/AppLayout.vue';
import AppMainLayout from '@/layouts/Admin/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Tenant } from '@/types/Admin/tenants';
import { Head, Link, router } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { SquareArrowOutUpRight } from 'lucide-vue-next';
import { computed, h, reactive } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

defineProps<{
    tenants: PaginateData<Tenant[]>;
}>();

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

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Link, { href: route('dashboard', { tenant: tenant.id }), asChild: true }, () =>
                    h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full' }, () => h(SquareArrowOutUpRight, { class: 'size-3' })),
                ),
            ]);
        },
    },
    {
        accessorKey: 'id',
        header: () => h('div', null, 'ID'),
        cell: ({ row }) => h('div', null, row.getValue('id')),
    },
    {
        accessorKey: 'name',
        header: () => h('div', null, 'Name'),
        cell: ({ row }) => h('div', null, row.getValue('name')),
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
