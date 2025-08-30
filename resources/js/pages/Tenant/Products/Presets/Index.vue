<script setup lang="ts">
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination/types';
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { DataTable } from '@/components/shared/table';
import type { Filter, SearchConfig, VisibilityState } from '@/components/shared/table/types';
import { Tooltip } from '@/components/shared/tooltip';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Machine } from '@/types/Tenant/machines';
import type { Product, ProductPreset, ProductPresetWithMachine } from '@/types/Tenant/products';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Loader, Pencil, Trash2 } from 'lucide-vue-next';
import slug from 'slug';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    presets: PaginateData<ProductPresetWithMachine[]>;
    options: {
        machines: SelectOption<Machine['id']>[];
        cavity_types: SelectOption<ProductPreset['cavity_type']>[];
        cycle_time_types: SelectOption<ProductPreset['cycle_time_type']>[];
        statuses: SwitchOption<ProductPresetWithMachine['status']>[];
    };
}>();

const { tenant } = useTenant();

const routeParams = computed(() => route().params);

const filter = reactive<Filter>({
    search: routeParams.value.search,
    entries: routeParams.value.entries || '10',
});

const setting = reactive({
    create: {
        dialogIsOpen: false,
    },
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
        title: 'Presets',
        href: route('products.presets.index', { tenant: tenant?.id || '', product: props.product.id }),
    },
];

const filterChangeHandler = (filter: Filter) =>
    router.visit(route('products.presets.index', { ...pickBy(filter), tenant: tenant?.id || '', product: props.product.id }));

const columnVisibility = <VisibilityState<Partial<ProductPresetWithMachine>>>{
    id: false,
    description: false,
};

const columns: ColumnDef<ProductPresetWithMachine>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const preset = row.original;
            return h('div', { class: 'flex items-center gap-2' }, [
                h(Tooltip, { text: 'Edit' }, () =>
                    h(
                        Link,
                        {
                            href: route('products.presets.edit', { tenant: tenant?.id || '', product: props.product.id, preset: preset.id }),
                            asChild: true,
                        },
                        () => h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full' }, () => h(Pencil, { class: 'size-3' })),
                    ),
                ),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${preset.name}`,
                        route: route('products.presets.destroy', { tenant: tenant?.id || '', product: props.product.id, preset: preset.id }),
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
        accessorKey: 'status',
        header: () => h('div', null, 'Status'),
        cell: ({ row }) => {
            const { status, status_label } = row.original;
            return h(Badge, { variant: status ? 'default' : 'destructive' }, () => status_label);
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
];

const defaultStatus = computed(() => props.options.statuses.find((status) => status.is_default)?.value);

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.status ? status.value : !status.value))?.name);

const form = useForm<{
    machine_id: Machine['id'] | '';
    name: string;
    code: string;
    description: string;
    cavity_quantity: number | '';
    cavity_type: ProductPreset['cavity_type'] | '';
    cycle_time: number | '';
    cycle_time_type: ProductPreset['cycle_time_type'] | '';
    status: ProductPreset['status'];
}>({
    machine_id: '',
    name: '',
    code: '',
    description: '',
    cavity_quantity: '',
    cavity_type: '',
    cycle_time: '',
    cycle_time_type: '',
    status: defaultStatus.value !== undefined ? defaultStatus.value : 1,
});

const config = reactive({
    status: !!form.status,
});

const create = () =>
    form.post(route('products.presets.store', { tenant: tenant?.id || '', product: props.product.id }), {
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
        const value = props.options.statuses.find((status) => (newVal ? status.value === 1 : status.value === 0))?.value;

        if (value !== undefined) {
            form.status = value;
        }
    },
);
</script>

<template>
    <Head title="Product Presets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Create Preset" v-model:open="setting.create.dialogIsOpen">
                        <template #trigger>
                            <Button class="cursor-pointer">Create</Button>
                        </template>
                        <form @submit.prevent="create" class="space-y-4">
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Name</Label>
                                <Input type="text" placeholder="Enter Name" v-model:model-value="form.name" />
                                <p v-if="form.errors.name" class="text-destructive">{{ form.errors.name }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Code</Label>
                                <Input type="text" placeholder="Enter Code" v-model:model-value="form.code" />
                                <p v-if="form.errors.code" class="text-destructive">{{ form.errors.code }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Description</Label>
                                <Textarea placeholder="Enter Description" v-model:model-value="form.description" />
                                <p v-if="form.errors.description" class="text-destructive">{{ form.errors.description }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Machine</Label>
                                <Select
                                    :options="options.machines"
                                    placeholder="Select Machine"
                                    v-model:model-value="form.machine_id"
                                    trigger-class="w-full"
                                />
                                <p v-if="form.errors.machine_id" class="text-destructive">{{ form.errors.machine_id }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Cavity Quantity</Label>
                                <Input
                                    type="number"
                                    placeholder="Enter Cavity Quantity"
                                    v-model:model-value.number="form.cavity_quantity"
                                    min="0"
                                    step=".01"
                                />
                                <p v-if="form.errors.cavity_quantity" class="text-destructive">{{ form.errors.cavity_quantity }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Cavity Type</Label>
                                <Select
                                    :options="options.cavity_types"
                                    placeholder="Select Cavity Type"
                                    v-model:model-value="form.cavity_type"
                                    trigger-class="w-full"
                                />
                                <p v-if="form.errors.cavity_type" class="text-destructive">{{ form.errors.cavity_type }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Cycle Time</Label>
                                <Input type="number" placeholder="Enter Cycle Time" v-model:model-value.number="form.cycle_time" min="0" step=".01" />
                                <p v-if="form.errors.cycle_time" class="text-destructive">{{ form.errors.cycle_time }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Cycle Time Type</Label>
                                <Select
                                    :options="options.cycle_time_types"
                                    placeholder="Select Cycle Time Type"
                                    v-model:model-value="form.cycle_time_type"
                                    trigger-class="w-full"
                                />
                                <p v-if="form.errors.cycle_time_type" class="text-destructive">{{ form.errors.cycle_time_type }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label class="mb-1">Status</Label>
                                <div class="flex items-center space-x-2">
                                    <Switch class="cursor-pointer" v-model:model-value="config.status" />
                                    <Label>{{ statusDisplay }}</Label>
                                </div>
                                <p v-if="form.errors.status" class="text-destructive">{{ form.errors.status }}</p>
                            </div>
                            <div class="flex gap-2">
                                <Button type="submit" class="cursor-pointer" :disabled="form.processing">
                                    <Loader v-if="form.processing" class="animate-spin" /> Create
                                </Button>
                            </div>
                        </form>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        :columns="columns"
                        :paginate-data="presets"
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
