<script setup lang="ts">
import { Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination/types';
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { DataTable } from '@/components/shared/table';
import type { Filter, SearchConfig, VisibilityState } from '@/components/shared/table/types';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Product, ProductPrice } from '@/types/Tenant/products';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { computed, h, reactive } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    prices: PaginateData<ProductPrice[]>;
    options: {
        statuses: SwitchOption<number>[];
        currencies: SelectOption<string>[];
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
        title: 'Prices',
        href: route('products.prices.index', { tenant: tenant?.id || '', product: props.product.id }),
    },
];

const filterChangeHandler = (filter: Filter) =>
    router.visit(route('products.prices.index', { ...pickBy(filter), tenant: tenant?.id || '', product: props.product.id }));

const columnVisibility = <VisibilityState<Partial<ProductPrice>>>{
    id: false,
};

const columns: ColumnDef<ProductPrice>[] = [
    // {
    //     accessorKey: 'actions',
    //     header: () => h('div', null, 'Actions'),
    //     cell: ({ row }) => {
    //         const machine = row.original;

    //         return h('div', { class: 'flex items-center gap-2' }, [
    //             h(Link, { href: route('machines.edit', { tenant: tenant?.id || '', machine: machine.id }), asChild: true }, () =>
    //                 h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full' }, () => h(Pencil, { class: 'size-3' })),
    //             ),
    //             h(
    //                 DeleteDialog,
    //                 {
    //                     title: `Delete ${machine.name}`,
    //                     route: route('machines.destroy', { tenant: tenant?.id || '', machine: machine.id }),
    //                 },
    //                 () =>
    //                     h(Button, { class: 'h-auto size-6 cursor-pointer rounded-full', variant: 'destructive' }, () =>
    //                         h(Trash2, { class: 'size-3' }),
    //                     ),
    //             ),
    //         ]);
    //     },
    // },
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
        accessorKey: 'currency',
        header: () => h('div', null, 'Currency'),
        cell: ({ row }) => h('div', null, row.getValue('currency')),
    },
    {
        accessorKey: 'amount',
        header: () => h('div', null, 'Amount'),
        cell: ({ row }) => h('div', null, row.getValue('amount')),
    },
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.is_active ? status.value : !status.value))?.name);

const form = useForm<{
    currency: string;
    amount: number | '';
    is_active: boolean;
}>({
    currency: '',
    amount: '',
    is_active: true,
});

const create = () =>
    form.post(route('products.prices.store', { tenant: tenant?.id || '', product: props.product.id }), {
        onSuccess: () => {
            form.reset();
            setting.create.dialogIsOpen = false;
        },
    });
</script>

<template>
    <Head title="Product Price" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Create Price" v-model:open="setting.create.dialogIsOpen">
                        <template #trigger>
                            <Button class="cursor-pointer">Create</Button>
                        </template>
                        <form @submit.prevent="create" class="space-y-4">
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <Label>Currency</Label>
                                <Select
                                    :options="options.currencies"
                                    placeholder="Select Currency"
                                    v-model:model-value="form.currency"
                                    trigger-class="w-full"
                                />
                                <p v-if="form.errors.currency" class="text-destructive">{{ form.errors.currency }}</p>
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
                        :paginate-data="prices"
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
