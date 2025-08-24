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
import type { Material } from '@/types/Tenant/materials';
import type { Product, ProductPrice } from '@/types/Tenant/products';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { CircleDollarSign, Loader, Pencil, Trash2 } from 'lucide-vue-next';
import slug from 'slug';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    products: PaginateData<Product[]>;
    options: {
        statuses: SwitchOption<number>[];
        materials: SelectOption<Material['id']>[];
        currencies: SelectOption<ProductPrice['currency']>[];
        shelf_life_types: SelectOption<Product['shelf_life_type']>[];
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
];

const filterChangeHandler = (filter: Filter) => {
    router.visit(route('products.index', { ...pickBy(filter), tenant: tenant?.id || '' }));
};

const columnVisibility = <VisibilityState<Partial<Product>>>{
    id: false,
    description: false,
};

const columns: ColumnDef<Product>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const product = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Tooltip, { text: 'Prices' }, () =>
                    h(Link, { href: route('products.prices.index', { tenant: tenant?.id || '', product: product.id }), asChild: true }, () =>
                        h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full' }, () => h(CircleDollarSign, { class: 'size-3' })),
                    ),
                ),
                h(Tooltip, { text: 'Edit' }, () =>
                    h(Link, { href: route('products.edit', { tenant: tenant?.id || '', product: product.id }), asChild: true }, () =>
                        h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full' }, () => h(Pencil, { class: 'size-3' })),
                    ),
                ),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${product.name}`,
                        route: route('products.destroy', { tenant: tenant?.id || '', product: product.id }),
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
        accessorKey: 'is_active',
        header: () => h('div', null, 'Active'),
        cell: ({ row }) => {
            const { is_active, is_active_display } = row.original;

            return h(Badge, { variant: is_active ? 'default' : 'destructive' }, () => is_active_display);
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
        accessorKey: 'shelf_life_duration',
        header: () => h('div', null, 'Shelf Life Duration'),
        cell: ({ row }) => h('div', null, row.original.shelf_life_duration || ''),
    },
    {
        accessorKey: 'shelf_life_type',
        header: () => h('div', null, 'Shelf Life Type'),
        cell: ({ row }) => h('div', null, row.original.shelf_life_type || ''),
    },
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.is_active ? status.value : !status.value))?.name);

const form = useForm<{
    name: string;
    code: string;
    description: string;
    shelf_life_duration: string;
    shelf_life_type: Product['shelf_life_type'] | '';
    is_active: boolean;
}>({
    name: '',
    code: '',
    description: '',
    shelf_life_duration: '',
    shelf_life_type: '',
    is_active: true,
});

const create = () =>
    form.post(route('products.store', { tenant: tenant?.id || '' }), {
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
    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Create Product" v-model:open="setting.create.dialogIsOpen">
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
                                <div class="grid gap-1.5 md:grid-cols-2">
                                    <div class="grid gap-1.5">
                                        <Label>Shelf Life Duration</Label>
                                        <Input
                                            type="number"
                                            placeholder="Enter Shelf Life Duration"
                                            v-model:model-value="form.shelf_life_duration"
                                            min="0.01"
                                            step=".01"
                                            class="w-full"
                                        />
                                    </div>
                                    <div class="grid gap-1.5">
                                        <Label>Shelf Life Type</Label>
                                        <Select
                                            :options="options.shelf_life_types"
                                            placeholder="Select Shelf Life Type"
                                            v-model:model-value="form.shelf_life_type"
                                            trigger-class="w-full"
                                        />
                                    </div>
                                </div>
                                <p v-if="form.errors.shelf_life_duration" class="text-destructive">{{ form.errors.shelf_life_duration }}</p>
                                <p v-if="form.errors.shelf_life_type" class="text-destructive">{{ form.errors.shelf_life_type }}</p>
                            </div>
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label class="mb-1">Status</Label>
                                <div class="flex items-center space-x-2">
                                    <Switch class="cursor-pointer" v-model:model-value="form.is_active" />
                                    <Label>{{ statusDisplay }}</Label>
                                </div>
                                <p v-if="form.errors.is_active" class="text-destructive">{{ form.errors.is_active }}</p>
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
                        :paginate-data="products"
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
