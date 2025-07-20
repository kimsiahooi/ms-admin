<script setup lang="ts">
import { Dialog } from '@/components/shared/dialog';
import type { DialogMethodType, DialogType } from '@/components/shared/dialog/types';
import { ErrorMessages } from '@/components/shared/error';
import type { PaginateData } from '@/components/shared/pagination/types';
import { MultiSelect, Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
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
import type { ProductPrice, ProductWithMaterialsAndprices } from '@/types/Tenant/products';
import type { Method } from '@inertiajs/core';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Minus, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import slug from 'slug';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    products: PaginateData<ProductWithMaterialsAndprices[]>;
    statuses: SwitchOption<number>[];
    options: {
        materials: SelectOption<number>[];
        currencies: SelectOption<ProductPrice['currency']>[];
        shelf_life_types: SelectOption<string>[];
    };
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
        title: 'Products',
        href: route('products.index', { tenant: tenant.value }),
    },
];

const filterChangeHandler = (filter: Filter) => {
    router.visit(route('products.index', { ...pickBy(filter), tenant: tenant.value }));
};

const columnVisibility = <VisibilityState<Partial<ProductWithMaterialsAndprices>>>{
    description: false,
};

const columns: ColumnDef<ProductWithMaterialsAndprices>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const product = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full', onClick: () => dialogHandler('update', product) }, () =>
                    h(Pencil, { class: 'size-3' }),
                ),
                h(
                    Button,
                    { class: 'h-auto size-6 cursor-pointer rounded-full', variant: 'destructive', onClick: () => dialogHandler('destroy', product) },
                    () => h(Trash2, { class: 'size-3' }),
                ),
            ]);
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
        accessorKey: 'shelf_life_duration',
        header: () => h('div', null, 'Shelf Life Duration'),
        cell: ({ row }) => h('div', null, row.getValue('shelf_life_duration')),
    },
    {
        accessorKey: 'shelf_life_type',
        header: () => h('div', null, 'Shelf Life type'),
        cell: ({ row }) => {
            const shelf_life_type = row.original.shelf_life_type_display || '';
            return h('div', null, shelf_life_type);
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

const dialog = reactive<DialogType<ProductWithMaterialsAndprices>>({
    type: null,
    title: '',
    isOpen: false,
    data: null,
});

const form = useForm<{
    name: string;
    code: string;
    description: string;
    shelf_life_duration: number | '';
    shelf_life_type: string;
    prices: {
        currency: ProductPrice['currency'] | '';
        value: number | '';
    }[];
    is_active: boolean;
    materials: number[];
}>(dialog.type || '', {
    name: '',
    code: '',
    description: '',
    shelf_life_duration: '',
    shelf_life_type: '',
    prices: [{ currency: '', value: '' }],
    is_active: true,
    materials: [],
});

const statusDisplay = computed(() => props.statuses.find((status) => (form.is_active ? status.value : !status.value))?.name);

const dialogButtonLabel = computed(() => (dialog.type === 'store' ? 'Create' : dialog.type === 'update' ? 'Update' : 'Delete'));
const dialogButtonVariant = computed<ButtonVariants['variant']>(() => (dialog.type === 'destroy' ? 'destructive' : 'default'));

const dialogHandler = (type: DialogMethodType, product?: ProductWithMaterialsAndprices) => {
    switch (type) {
        case 'store':
            dialog.title = 'Create Product';
            break;
        case 'update':
            if (product) {
                dialog.data = product;
                form.name = product.name;
                form.code = product.code;
                form.description = product.description || '';
                form.shelf_life_duration = product.shelf_life_duration ? +product.shelf_life_duration : '';
                form.shelf_life_type = product.shelf_life_type || '';
                form.prices = product.prices.map((price) => ({ currency: price.currency, value: +price.price }));
                form.is_active = product.is_active;
                form.materials = product.materials.filter((material) => material.is_active).map((material) => material.id);
            }
            dialog.title = `Edit ${dialog.data?.name || 'Product'}`;
            break;
        case 'destroy':
            if (product) {
                dialog.data = product;
            }
            dialog.title = `Delete ${dialog.data?.name || 'Product'}`;
    }
    dialog.type = type;
    dialog.isOpen = true;
};

const submit = () => {
    if (dialog.type) {
        const fetchLink =
            dialog.type === 'store'
                ? route('products.store', { tenant: tenant.value })
                : dialog.type === 'update'
                  ? route('products.update', { tenant: tenant.value, product: dialog.data?.id || '' })
                  : route('products.destroy', { tenant: tenant.value, product: dialog.data?.id || '' });

        const method: Method = dialog.type === 'store' ? 'post' : dialog.type === 'update' ? 'put' : 'delete';

        form.transform((data) => (dialog.type !== 'destroy' ? data : {})).submit(method, fetchLink, {
            onSuccess: () => {
                form.reset();
                dialog.isOpen = false;
            },
        });
    }
};

const priceHandler = (index: number) => {
    if (!index) {
        form.prices = [...form.prices, { currency: '', value: '' }];
    } else {
        form.prices = form.prices.filter((_, i) => index !== i);
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
    <Head title="Products" />

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
                            <div class="grid gap-1.5 md:grid-cols-2">
                                <div class="grid gap-1.5">
                                    <Label>Shelf Life Duration</Label>
                                    <Input
                                        type="number"
                                        placeholder="Enter Shelf Life Day(s)"
                                        v-model:model-value.number="form.shelf_life_duration"
                                        min="0"
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
                            <Label>Unit price</Label>
                            <div v-for="(price, index) in form.prices" :key="index">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <Select
                                            :options="options.currencies"
                                            placeholder="Select Currency"
                                            v-model:model-value="price.currency"
                                            trigger-class="w-full"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <Input
                                            type="number"
                                            step=".01"
                                            min="0"
                                            placeholder="Enter Unit Price"
                                            v-model:model-value.number="price.value"
                                            class="w-full"
                                        />
                                    </div>
                                    <div>
                                        <Button
                                            type="button"
                                            size="icon"
                                            class="cursor-pointer"
                                            :variant="!index ? 'default' : 'destructive'"
                                            @click="priceHandler(index)"
                                        >
                                            <Plus v-if="!index" />
                                            <Minus v-else />
                                        </Button>
                                    </div>
                                </div>
                                <ErrorMessages :error-key="`prices.${index}`" />
                            </div>
                        </div>
                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label>Materials</Label>
                            <MultiSelect
                                :options="options.materials"
                                placeholder="Select Materials"
                                v-model:model-value="form.materials"
                                trigger-class="w-full"
                            />
                            <ErrorMessages error-key="materials" />
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
