<script setup lang="ts">
import { Dialog } from '@/components/shared/dialog';
import type { DialogMethodType, DialogType } from '@/components/shared/dialog/types';
import type { PaginateData } from '@/components/shared/pagination/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { DataTable } from '@/components/shared/table';
import type { Filter, SearchConfig, VisibilityState } from '@/components/shared/table/types';
import { Badge } from '@/components/ui/badge';
import { Button, type ButtonVariants } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { AppPageProps, BreadcrumbItem } from '@/types';
import type { Machine } from '@/types/Tenant/machines';
import type { Method } from '@inertiajs/core';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import slug from 'slug';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    machines: PaginateData<Machine[]>;
    statuses: SwitchOption<number>[];
}>();

const { formatDateTime } = useFormatDateTime();

const page = usePage<AppPageProps>();
const tenant = computed(() => page.props.tenant?.id || '');
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
        href: route('dashboard', { tenant: tenant.value }),
    },
    {
        title: 'Machines',
        href: route('machines.index', { tenant: tenant.value }),
    },
];

const filterChangeHandler = (filter: Filter) => {
    router.visit(route('machines.index', { ...pickBy(filter), tenant: tenant.value }));
};

const columnVisibility = <VisibilityState<Partial<Machine>>>{
    description: false,
};

const columns: ColumnDef<Machine>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const machine = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full', onClick: () => dialogHandler('update', machine) }, () =>
                    h(Pencil, { class: 'size-3' }),
                ),
                h(
                    Button,
                    { class: 'h-auto size-6 cursor-pointer rounded-full', variant: 'destructive', onClick: () => dialogHandler('destroy', machine) },
                    () => h(Trash2, { class: 'size-3' }),
                ),
            ]);
        },
    },
    {
        accessorKey: 'id',
        header: () => h('div', null, 'ID'),
        cell: ({ row }) => {
            const id = row.original.id;
            return h('div', null, id);
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
        accessorKey: 'is_active',
        header: () => h('div', null, 'Active'),
        cell: ({ row }) => {
            const { is_active, is_active_display } = row.original;

            return h(Badge, { variant: is_active ? 'default' : 'destructive' }, () => is_active_display);
        },
    },
    {
        accessorKey: 'created_at',
        header: () => h('div', null, 'Created At'),
        cell: ({ row }) => {
            const value = formatDateTime(row.original.created_at);

            if (!value) {
                return h('div', null);
            }

            return h('div', null, value);
        },
    },
    {
        accessorKey: 'updated_at',
        header: () => h('div', null, 'Updated At'),
        cell: ({ row }) => {
            const value = formatDateTime(row.original.updated_at);

            if (!value) {
                return h('div', null);
            }

            return h('div', null, value);
        },
    },
];

const dialog = reactive<DialogType<Machine>>({
    type: null,
    title: '',
    isOpen: false,
    data: null,
});

const form = useForm<{
    name: string;
    code: string;
    description: string;
    is_active: boolean;
}>(dialog.type || '', {
    name: '',
    code: '',
    description: '',
    is_active: true,
});

const statusDisplay = computed(() => props.statuses.find((status) => (form.is_active ? status.value : !status.value))?.name);

const dialogButtonLabel = computed(() => (dialog.type === 'store' ? 'Create' : dialog.type === 'update' ? 'Update' : 'Delete'));
const dialogButtonVariant = computed<ButtonVariants['variant']>(() => (dialog.type === 'destroy' ? 'destructive' : 'default'));

const dialogHandler = (type: DialogMethodType, machine?: Machine) => {
    switch (type) {
        case 'store':
            dialog.title = 'Create Machine';
            break;
        case 'update':
            if (machine) {
                dialog.data = machine;
                form.name = machine.name;
                form.code = machine.code;
                form.description = machine.description || '';
                form.is_active = machine.is_active;
            }
            dialog.title = `Edit ${dialog.data?.name || 'Machine'}`;
            break;
        case 'destroy':
            if (machine) {
                dialog.data = machine;
            }
            dialog.title = `Delete ${dialog.data?.name || 'Machine'}`;
    }
    dialog.type = type;
    dialog.isOpen = true;
};

const submit = () => {
    if (dialog.type) {
        const fetchLink =
            dialog.type === 'store'
                ? route('machines.store', { tenant: tenant.value })
                : dialog.type === 'update'
                  ? route('machines.update', { tenant: tenant.value, machine: dialog.data?.id || '' })
                  : route('machines.destroy', { tenant: tenant.value, machine: dialog.data?.id || '' });

        const method: Method = dialog.type === 'store' ? 'post' : dialog.type === 'update' ? 'put' : 'delete';

        form.transform((data) => (dialog.type !== 'destroy' ? data : {})).submit(method, fetchLink, {
            onSuccess: () => {
                form.reset();
                dialog.isOpen = false;
            },
        });
    }
};

watch(
    () => dialog.isOpen,
    (newValue) => {
        if (!newValue) {
            dialog.data = null;
            form.clearErrors();
            form.reset();
        }
    },
);

watch([() => form.name, () => dialog.type], ([newName, newType]) => {
    if (newType === 'store') {
        form.code = slug(newName);
    }
});
</script>

<template>
    <Head title="Machines" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog v-model:open="dialog.isOpen" :dialog="dialog" @submit="submit">
                        <template #button>
                            <Button class="cursor-pointer" @click="dialogHandler('store')">Create</Button>
                        </template>
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
                            <Label class="mb-1">Status</Label>
                            <div class="flex items-center space-x-2">
                                <Switch class="cursor-pointer" v-model:model-value="form.is_active" />
                                <Label>{{ statusDisplay }}</Label>
                            </div>
                            <p v-if="form.errors.is_active" class="text-destructive">{{ form.errors.is_active }}</p>
                        </div>
                        <template #submit-button>
                            <Button type="submit" class="cursor-pointer" :variant="dialogButtonVariant" :disabled="form.processing">
                                {{ dialogButtonLabel }}
                            </Button>
                        </template>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        :columns="columns"
                        :paginate-data="machines"
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
