<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination';
import { ToggleStatus, type SwitchOption } from '@/components/shared/switch';
import type { VisibilityState } from '@/components/shared/table';
import { DataTable } from '@/components/shared/table';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Filter } from '@/types/shared';
import { Status, StatusLabel, type Company, type StatusBadgeLabel } from '@/types/Tenant/companies';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Factory, Pencil, Trash2 } from 'lucide-vue-next';
import slug from 'slug';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    companies: PaginateData<Company[]>;
    options: {
        statuses: SwitchOption<Company['status'], StatusBadgeLabel>[];
    };
}>();

const { tenant } = useTenant();
const { formatDateTime } = useFormatDateTime();

const routeParams = computed(() => route().params);

const filter = reactive<Filter>({
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
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Companies',
        href: route('companies.index', { tenant: tenant?.id || '' }),
    },
];

const search = () =>
    router.visit(route('companies.index', { ...pickBy(filter), tenant: tenant?.id || '' }), {
        preserveScroll: true,
        preserveState: true,
    });
const reset = () => router.visit(route('companies.index', { tenant: tenant?.id || '' }));

const columnVisibility = <VisibilityState<Partial<Company>>>{
    id: false,
    description: false,
};

const columns: ColumnDef<Company>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const company = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(ToggleStatus, {
                    value: company.status_switch,
                    method: 'put',
                    href: route('companies.toggleStatus', { tenant: tenant?.id || '', company: company.id }),
                }),
                h(ActionButton, {
                    text: 'Edit',
                    href: route('companies.edit', { tenant: tenant?.id || '', company: company.id }),
                    icon: Pencil,
                }),
                h(ActionButton, {
                    text: 'Branches',
                    href: route('companies.branches.index', { tenant: tenant?.id || '', company: company.id }),
                    icon: Factory,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${company.name}`,
                        route: route('companies.destroy', { tenant: tenant?.id || '', company: company.id }),
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

const defaultStatus = computed<Company['status']>(() => props.options.statuses.find((status) => status.is_default)?.value ?? Status.ACTIVE);

const statusDisplay = computed<StatusBadgeLabel>(
    () => props.options.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.ACTIVE],
);

const form = useForm({
    name: '',
    code: '',
    description: '',
    status: defaultStatus.value,
});

const config = reactive({
    status: form.status === Status.ACTIVE,
});

const submit = () =>
    form.post(route('companies.store', { tenant: tenant?.id || '' }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            setting.create.dialogIsOpen = false;
        },
    });

watch(
    () => form.name,
    (newName) => {
        form.code = slug(newName);
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
    <Head title="Companies" />

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
                    <Dialog title="Create Company" v-model:open="setting.create.dialogIsOpen">
                        <form @submit.prevent="submit" class="space-y-4">
                            <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                            <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                            <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                            <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                            <FormButton type="submit" :disabled="form.processing" :loading="form.processing" />
                        </form>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="companies"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
