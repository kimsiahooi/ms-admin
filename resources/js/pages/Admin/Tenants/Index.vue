<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { FormButton, FormInput, FormSwitch } from '@/components/shared/custom/form';
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination/types';
import type { SwitchOption } from '@/components/shared/switch';
import { DataTable } from '@/components/shared/table';
import type { VisibilityState } from '@/components/shared/table/types';
import { Button } from '@/components/ui/button';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Admin/AppLayout.vue';
import AppMainLayout from '@/layouts/Admin/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Status, StatusLabel, type StatusBadgeLabel, type Tenant } from '@/types/Admin/tenants';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import slug from 'slug';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    tenants: PaginateData<Tenant[]>;
    options: {
        statuses: SwitchOption<Tenant['status'], StatusBadgeLabel>[];
    };
}>();

const { formatDateTime } = useFormatDateTime();

const routeParams = computed(() => route().params);

const filter = reactive<Record<string, string>>({
    search: routeParams.value.search,
    entries: routeParams.value.entries || '10',
    status: routeParams.value.status,
});

const setting = reactive({
    create: {
        dialogIsOpen: false,
    },
});

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

const search = () => router.visit(route('admin.tenants.index', { ...pickBy(filter) }));
const reset = () => router.visit(route('admin.tenants.index'));

const columnVisibility = <VisibilityState<Partial<Tenant>>>{};

const columns: ColumnDef<Tenant>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const tenant = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(ActionButton, {
                    text: 'Edit',
                    href: route('admin.tenants.edit', { tenant: tenant.id }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${tenant.name}`,
                        route: route('admin.tenants.destroy', { tenant: tenant.id }),
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
            const { status_badge } = row.original;

            return h(StatusBadge, { statusBadge: status_badge });
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

const defaultStatus = computed<Tenant['status']>(() => props.options.statuses.find((status) => status.is_default)?.value ?? Status.ACTIVE);

const statusDisplay = computed<StatusBadgeLabel>(
    () => props.options.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.ACTIVE],
);

const form = useForm({
    id: '',
    name: '',
    status: defaultStatus.value,
});

const config = reactive({
    status: !!form.status,
});

const submit = () =>
    form.post(route('admin.tenants.store'), {
        onSuccess: () => {
            form.reset();
            setting.create.dialogIsOpen = false;
        },
    });

watch(
    () => form.name,
    (newName) => {
        form.id = slug(newName);
    },
);

watch(
    () => config.status,
    (newVal) => {
        const value = props.options.statuses.find((status) => (newVal ? status.value === Status.ACTIVE : status.value === Status.INACTIVE))?.value;

        if (value !== undefined) {
            form.status = value;
        }
    },
);
</script>

<template>
    <Head title="Tenants" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <FilterCard @search="search" @reset="reset">
                    <FilterInput label="Name" placeholder="Search ID, Name" v-model:model-value="filter.search" />
                    <FilterSelect
                        label="Status"
                        placeholder="Select Status"
                        :options="options.statuses"
                        multiple
                        v-model:model-value="filter.status"
                    />
                </FilterCard>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Create Company" v-model:open="setting.create.dialogIsOpen">
                        <form @submit.prevent="submit" class="space-y-4">
                            <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                            <FormInput label="ID" :error="form.errors.id" v-model:model-value="form.id" />
                            <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                            <FormButton type="submit" :disabled="form.processing" />
                        </form>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="tenants"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
