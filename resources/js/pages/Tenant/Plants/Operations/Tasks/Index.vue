<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination';
import type { SelectOption } from '@/components/shared/select';
import type { SwitchOption } from '@/components/shared/switch';
import { StatusSwitch } from '@/components/shared/switch';
import type { VisibilityState } from '@/components/shared/table';
import { DataTable } from '@/components/shared/table';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Filter } from '@/types/shared';
import type { Plant } from '@/types/Tenant/plants';
import { Operation, Status, StatusLabel } from '@/types/Tenant/plants/operations';
import { Task } from '@/types/Tenant/plants/operations/tasks';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import slug from 'slug';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    plant: Plant;
    operation: Operation;
    tasks: PaginateData<Task[]>;
    options: {
        select: {
            statuses: SelectOption<Task['status']['value']>[];
        };
        switch: {
            statuses: SwitchOption[];
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

const setting = reactive({
    create: {
        dialogIsOpen: false,
    },
});

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Plants',
        href: route('plants.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.plant.name,
        href: '#',
    },
    {
        title: 'Operations',
        href: route('plants.operations.index', { tenant: tenant?.id || '', plant: props.plant.id }),
    },
    {
        title: props.operation.name,
        href: '#',
    },
    {
        title: 'Tasks',
        href: route('plants.operations.tasks.index', { tenant: tenant?.id || '', plant: props.plant.id, operation: props.operation.id }),
    },
]);

const search = () =>
    router.visit(
        route('plants.operations.tasks.index', {
            ...pickBy(filter.data()),
            tenant: tenant?.id || '',
            plant: props.plant.id,
            operation: props.operation.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
const reset = () => {
    filter.reset();
    search();
};

const columns: ColumnDef<Task>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const task = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(StatusSwitch, {
                    value: task.status.switch,
                    method: 'put',
                    href: route('plants.operations.tasks.toggleStatus', {
                        tenant: tenant?.id || '',
                        plant: props.plant.id,
                        operation: props.operation.id,
                        task: task.id,
                    }),
                }),
                h(ActionButton, {
                    text: 'Edit',
                    href: route('plants.operations.tasks.edit', {
                        tenant: tenant?.id || '',
                        plant: props.plant.id,
                        operation: props.operation.id,
                        task: task.id,
                    }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${task.name}`,
                        route: route('plants.operations.tasks.destroy', {
                            tenant: tenant?.id || '',
                            plant: props.plant.id,
                            operation: props.operation.id,
                            task: task.id,
                        }),
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

const columnVisibility: VisibilityState<Task> = {
    id: false,
    description: false,
};

const defaultStatus = computed<(typeof props.options.switch.statuses)[number]['value']>(
    () => !!props.options.switch.statuses.find((status) => status.is_default)?.value,
);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    name: '',
    code: '',
    description: '',
    status: defaultStatus.value,
});

const submit = () =>
    form.post(route('plants.operations.tasks.store', { tenant: tenant?.id || '', plant: props.plant.id, operation: props.operation.id }), {
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
</script>

<template>
    <Head title="Tasks" />

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
                    <Dialog title="Create Task" v-model:open="setting.create.dialogIsOpen">
                        <form @submit.prevent="submit" class="space-y-4">
                            <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                            <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                            <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                            <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="form.status" />
                            <FormButton type="submit" :disabled="form.processing" :loading="form.processing" />
                        </form>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="tasks"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
