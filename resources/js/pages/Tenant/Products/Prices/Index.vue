<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { FormButton, FormInput, FormSelect, FormSwitch } from '@/components/shared/custom/form';
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination/types';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { DataTable } from '@/components/shared/table';
import type { VisibilityState } from '@/components/shared/table/types';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Product } from '@/types/Tenant/products';
import type { ProductPrice, StatusLabel } from '@/types/Tenant/products/prices';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    prices: PaginateData<ProductPrice[]>;
    options: {
        statuses: SwitchOption<ProductPrice['status'], StatusLabel>[];
        currencies: SelectOption<ProductPrice['currency']>[];
    };
}>();

const { tenant } = useTenant();
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

const search = () => router.visit(route('products.prices.index', { ...pickBy(filter), tenant: tenant?.id || '', product: props.product.id }));
const reset = () => router.visit(route('products.prices.index', { tenant: tenant?.id || '', product: props.product.id }));

const columnVisibility = <VisibilityState<Partial<ProductPrice>>>{
    id: false,
};

const columns: ColumnDef<ProductPrice>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const price = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(ActionButton, {
                    text: 'Edit',
                    href: route('products.prices.edit', { tenant: tenant?.id || '', product: props.product.id, price: price.id }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Delete ${price.currency}`,
                        route: route('products.prices.destroy', { tenant: tenant?.id || '', product: props.product.id, price: price.id }),
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
        header: () => h('div', null, 'Active'),
        cell: ({ row }) => {
            const { status_label } = row.original;

            return h(StatusBadge, { statusLabel: status_label });
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

const defaultStatus = computed<ProductPrice['status']>(() => props.options.statuses.find((status) => status.is_default)?.value ?? 'ACTIVE');

const statusDisplay = computed<StatusLabel>(() => props.options.statuses.find((status) => status.value === form.status)?.name ?? 'Active');

const form = useForm<{
    currency: string;
    amount: number | '';
    status: ProductPrice['status'];
}>({
    currency: '',
    amount: '',
    status: defaultStatus.value,
});

const config = reactive({
    status: form.status === 'ACTIVE',
});

const submit = () =>
    form.post(route('products.prices.store', { tenant: tenant?.id || '', product: props.product.id }), {
        onSuccess: () => {
            form.reset();
            setting.create.dialogIsOpen = false;
        },
    });

watch(
    () => config.status,
    (newVal) => {
        const value = props.options.statuses.find((status) => (newVal ? status.value === 'ACTIVE' : status.value === 'INACTIVE'))?.value;

        if (value !== undefined) {
            form.status = value;
        }
    },
);
</script>

<template>
    <Head title="Product Prices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <FilterCard @search="search" @reset="reset">
                    <FilterInput label="Name" placeholder="Search ID, Currency" v-model:model-value="filter.search" />
                    <FilterSelect
                        label="Status"
                        placeholder="Select Status"
                        :options="options.statuses"
                        multiple
                        v-model:model-value="filter.status"
                    />
                </FilterCard>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Create Price" v-model:open="setting.create.dialogIsOpen">
                        <form @submit.prevent="submit" class="space-y-4">
                            <FormSelect
                                label="Currency"
                                :options="options.currencies"
                                v-model:model-value="form.currency"
                                :error="form.errors.currency"
                            />
                            <FormInput
                                label="Amount"
                                type="number"
                                :error="form.errors.amount"
                                v-model:model-value="form.amount"
                                step=".01"
                                min="0"
                            />
                            <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                            <FormButton type="submit" :disabled="form.processing" :loading="form.processing" />
                        </form>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="prices"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
