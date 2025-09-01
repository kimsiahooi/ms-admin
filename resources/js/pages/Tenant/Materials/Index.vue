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
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Material } from '@/types/Tenant/materials';
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
    materials: PaginateData<Material[]>;
    options: {
        statuses: SwitchOption<Material['status']>[];
        unit_types: SelectOption<Material['unit_type']>[];
    };
}>();

const { tenant } = useTenant();
const { formatDateTime } = useFormatDateTime();

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
        title: 'Materials',
        href: route('materials.index', { tenant: tenant?.id || '' }),
    },
];

const filterChangeHandler = (filter: Filter) => {
    router.visit(route('materials.index', { ...pickBy(filter), tenant: tenant?.id || '' }));
};

const columnVisibility = <VisibilityState<Partial<Material>>>{
    id: false,
    description: false,
};

const columns: ColumnDef<Material>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const material = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Tooltip, { text: 'Edit' }, () =>
                    h(Link, { href: route('materials.edit', { tenant: tenant?.id || '', material: material.id }), asChild: true }, () =>
                        h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full' }, () => h(Pencil, { class: 'size-3' })),
                    ),
                ),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${material.name}`,
                        route: route('materials.destroy', { tenant: tenant?.id || '', material: material.id }),
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
        accessorKey: 'unit_type',
        header: () => h('div', null, 'Unit Type'),
        cell: ({ row }) => h('div', null, row.original.unit_type_label || ''),
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

const defaultStatus = computed(() => props.options.statuses.find((status) => status.is_default)?.value);

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.status ? status.value : !status.value))?.name);

const form = useForm<{
    name: string;
    code: string;
    description: string;
    unit_type: Material['unit_type'] | '';
    status: Material['status'];
}>({
    name: '',
    code: '',
    description: '',
    unit_type: '',
    status: defaultStatus.value !== undefined ? defaultStatus.value : 1,
});

const config = reactive({
    status: !!form.status,
});

const create = () =>
    form.post(route('materials.store', { tenant: tenant?.id || '' }), {
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
    <Head title="Materials" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Create Material" v-model:open="setting.create.dialogIsOpen">
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
                                <Label>Unit Type</Label>
                                <Select
                                    :options="options.unit_types"
                                    placeholder="Select Unit Type"
                                    v-model:model-value="form.unit_type"
                                    trigger-class="w-full"
                                />
                                <p v-if="form.errors.unit_type" class="text-destructive">{{ form.errors.unit_type }}</p>
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
                        :paginate-data="materials"
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
