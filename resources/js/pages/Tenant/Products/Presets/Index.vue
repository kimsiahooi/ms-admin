<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { FormButton, FormInput, FormSelect, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination';
import type { SelectOption } from '@/components/shared/select';
import { StatusSwitch, type SwitchOption } from '@/components/shared/switch';
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
import type { Machine } from '@/types/Tenant/machines';
import type { Product } from '@/types/Tenant/products';
import { Status, StatusLabel, type ProductPreset, type ProductPresetWithMachine } from '@/types/Tenant/products/presets';
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
    product: Product;
    presets: PaginateData<ProductPresetWithMachine[]>;
    options: {
        select: {
            machines: SelectOption<Machine['id']>[];
            cavity_types: SelectOption<ProductPreset['cavity_type']>[];
            cycle_time_types: SelectOption<ProductPreset['cycle_time_type']>[];
            shelf_life_types: SelectOption<ProductPreset['shelf_life_type']>[];
            statuses: SelectOption<ProductPreset['status']['value']>[];
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
        title: 'Presets',
        href: route('products.presets.index', { tenant: tenant?.id || '', product: props.product.id }),
    },
];

const search = () =>
    router.visit(route('products.presets.index', { ...pickBy(filter.data()), tenant: tenant?.id || '', product: props.product.id }), {
        preserveScroll: true,
        preserveState: true,
    });
const reset = () => {
    filter.reset();
    search();
};

const columns: ColumnDef<ProductPresetWithMachine>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const preset = row.original;
            return h('div', { class: 'flex items-center gap-2' }, [
                h(StatusSwitch, {
                    value: preset.status.switch,
                    method: 'put',
                    href: route('products.presets.toggleStatus', { tenant: tenant?.id || '', product: props.product.id, preset: preset.id }),
                }),
                h(ActionButton, {
                    text: 'Edit',
                    href: route('products.presets.edit', { tenant: tenant?.id || '', product: props.product.id, preset: preset.id }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${preset.name}`,
                        route: route('products.presets.destroy', { tenant: tenant?.id || '', product: props.product.id, preset: preset.id }),
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
        accessorKey: 'machine',
        header: () => h('div', null, 'Machine'),
        cell: ({ row }) => {
            const { machine } = row.original;
            return machine
                ? h(
                      Link,
                      {
                          href: route('machines.index', { tenant: tenant?.id || '', search: machine?.id || '' }),
                      },
                      () => h(Button, { class: 'cursor-pointer', variant: 'link' }, () => machine?.name),
                  )
                : null;
        },
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
        accessorKey: 'cavity_quantity',
        header: () => h('div', null, 'Cavity Quantity'),
        cell: ({ row }) => h('div', null, row.getValue('cavity_quantity')),
    },
    {
        accessorKey: 'cavity_type',
        header: () => h('div', null, 'Cavity Type'),
        cell: ({ row }) => {
            const { cavity_type_label } = row.original;
            return h('div', null, cavity_type_label || '');
        },
    },
    {
        accessorKey: 'cycle_time',
        header: () => h('div', null, 'Cycle Time'),
        cell: ({ row }) => h('div', null, row.getValue('cycle_time')),
    },
    {
        accessorKey: 'cycle_time_type',
        header: () => h('div', null, 'Cycle Time Type'),
        cell: ({ row }) => {
            const { cycle_time_type_label } = row.original;
            return h('div', null, cycle_time_type_label || '');
        },
    },
    {
        accessorKey: 'shelf_life_duration',
        header: () => h('div', null, 'Shelf Life Duration'),
        cell: ({ row }) => h('div', null, row.original.shelf_life_duration || ''),
    },
    {
        accessorKey: 'shelf_life_type',
        header: () => h('div', null, 'Shelf Life Type'),
        cell: ({ row }) => h('div', null, row.original.shelf_life_type_label || ''),
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

const columnVisibility: VisibilityState<ProductPresetWithMachine> = {
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
    machine_id: '',
    name: '',
    code: '',
    description: '',
    cavity_quantity: '',
    cavity_type: '',
    cycle_time: '',
    cycle_time_type: '',
    shelf_life_duration: '',
    shelf_life_type: '',
    status: defaultStatus.value,
});

const submit = () =>
    form.post(route('products.presets.store', { tenant: tenant?.id || '', product: props.product.id }), {
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
    <Head title="Product Presets" />

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
                    <Dialog title="Create Preset" v-model:open="setting.create.dialogIsOpen">
                        <form @submit.prevent="submit" class="space-y-4">
                            <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                            <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                            <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                            <FormSelect
                                label="Machine"
                                :options="options.select.machines"
                                v-model:model-value="form.machine_id"
                                :error="form.errors.machine_id"
                            />
                            <FormInput
                                label="Cavity Quantity"
                                :error="form.errors.cavity_quantity"
                                v-model:model-value="form.cavity_quantity"
                                type="number"
                                min="0.01"
                                step=".01"
                            />
                            <FormSelect
                                label="Cavity Type"
                                :options="options.select.cavity_types"
                                v-model:model-value="form.cavity_type"
                                :error="form.errors.cavity_type"
                            />
                            <FormInput
                                label="Cycle Time"
                                :error="form.errors.cycle_time"
                                v-model:model-value="form.cycle_time"
                                type="number"
                                min="0.01"
                                step=".01"
                            />
                            <FormSelect
                                label="Cycle Time Type"
                                :options="options.select.cycle_time_types"
                                v-model:model-value="form.cycle_time_type"
                                :error="form.errors.cycle_time_type"
                            />
                            <FormInput
                                label="Shelf Life Duration"
                                :error="form.errors.shelf_life_duration"
                                v-model:model-value="form.shelf_life_duration"
                                type="number"
                                min="0.01"
                                step=".01"
                            />
                            <FormSelect
                                label="Shelf Life Type"
                                :options="options.select.shelf_life_types"
                                v-model:model-value="form.shelf_life_type"
                                :error="form.errors.shelf_life_type"
                            />
                            <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="form.status" />
                            <FormButton type="submit" :disabled="form.processing" :loading="form.processing" />
                        </form>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="presets"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
