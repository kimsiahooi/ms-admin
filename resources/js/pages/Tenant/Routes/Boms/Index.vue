<script setup lang="ts">
import { StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { FormButton, FormCombobox, FormSwitch } from '@/components/shared/custom/form';
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import { PaginateData } from '@/components/shared/pagination';
import type { SelectOption } from '@/components/shared/select';
import { StatusSwitch, type SwitchOption } from '@/components/shared/switch';
import type { VisibilityState } from '@/components/shared/table';
import { DataTable } from '@/components/shared/table';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { useTenant } from '@/composables/useTenant';
import { entryOptions } from '@/constants/entries/options';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Filter } from '@/types/shared';
import { Status, StatusLabel } from '@/types/Tenant/plants/departments';
import { ProductWithBoms } from '@/types/Tenant/products';
import { Route } from '@/types/Tenant/routes';
import { PivotProductBom } from '@/types/Tenant/routes/boms';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, h, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    route: Route;
    boms: PaginateData<PivotProductBom[]>;
    products: ProductWithBoms[];
    options: {
        select: {
            statuses: SelectOption<PivotProductBom['pivot']['status']['value']>[];
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
        title: 'Routes',
        href: route('routes.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.route.name,
        href: '#',
    },
    {
        title: 'Boms',
        href: route('routes.boms.index', { tenant: tenant?.id || '', route: props.route.id }),
    },
]);

const productOptions = computed<SelectOption<ProductWithBoms['id']>[]>(() =>
    props.products.map((product) => ({ name: product.name, value: product.id })),
);

const bomOptions = computed<SelectOption<ProductWithBoms['boms'][number]['id']>[]>(
    () => props.products.find((product) => product.id === form.product_id)?.boms.map((bom) => ({ name: bom.name, value: bom.id })) ?? [],
);

const search = () =>
    router.visit(
        route('routes.boms.index', {
            ...pickBy(filter.data()),
            tenant: tenant?.id || '',
            route: props.route.id,
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

const columns: ColumnDef<PivotProductBom>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const bom = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(StatusSwitch, {
                    value: bom.pivot.status.switch,
                    method: 'put',
                    href: route('routes.boms.toggleStatus', {
                        tenant: tenant?.id || '',
                        route: props.route.id,
                        bom: bom.pivot.id,
                    }),
                }),
                h(ActionButton, {
                    text: 'Edit',
                    href: route('routes.boms.edit', {
                        tenant: tenant?.id || '',
                        route: props.route.id,
                        bom: bom.pivot.id,
                    }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Detach ${bom.name}`,
                        route: route('routes.boms.destroy', {
                            tenant: tenant?.id || '',
                            route: props.route.id,
                            bom: bom.pivot.id,
                        }),
                        description: 'Are you sure you want to detach?',
                        buttonLabel: 'Detach',
                        asChild: false,
                    },
                    () =>
                        h(ActionButton, {
                            variant: 'destructive',
                            text: 'Detach',
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
            const { pivot } = row.original;

            return h(StatusBadge, { statusBadge: pivot.status.badge });
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
        cell: ({ row }) => {
            const { name } = row.original;
            return h('div', null, name);
        },
    },
    {
        accessorKey: 'code',
        header: () => h('div', null, 'Code'),
        cell: ({ row }) => {
            const { code } = row.original;
            return h('div', null, code);
        },
    },
    {
        accessorKey: 'created_at',
        header: () => h('div', null, 'Created At'),
        cell: ({ row }) => {
            const { pivot } = row.original;
            return h('div', null, formatDateTime(pivot.created_at) || '');
        },
    },
    {
        accessorKey: 'updated_at',
        header: () => h('div', null, 'Updated At'),
        cell: ({ row }) => {
            const { pivot } = row.original;
            return h('div', null, formatDateTime(pivot.updated_at) || '');
        },
    },
];

const columnVisibility: VisibilityState<PivotProductBom> = {
    id: false,
};

const defaultStatus = computed<(typeof props.options.switch.statuses)[number]['value']>(
    () => !!props.options.switch.statuses.find((status) => status.is_default)?.value,
);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    product_id: '',
    bom_id: '',
    status: defaultStatus.value,
});

const submit = () =>
    form.post(route('routes.boms.store', { tenant: tenant?.id || '', route: props.route.id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            setting.create.dialogIsOpen = false;
        },
    });

watch(
    () => form.product_id,
    () => {
        form.bom_id = '';
    },
);
</script>

<template>
    <Head title="Boms" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <FilterCard @search="search" @reset="reset">
                    <FilterInput label="Name" placeholder="Search ID, Name, Code" v-model:model-value="filter.search" />
                    <FilterSelect
                        label="Status"
                        placeholder="Select Status"
                        :options="options.select.statuses"
                        v-model:model-value="filter.status"
                        multiple
                    />
                </FilterCard>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Attach Product Bom" v-model:open="setting.create.dialogIsOpen" trigger-label="Attach">
                        <form @submit.prevent="submit" class="space-y-4">
                            <FormCombobox
                                :options="productOptions"
                                placeholder="Select Product"
                                label="Product"
                                v-model:model-value="form.product_id"
                                :error="form.errors.product_id"
                            />
                            <FormCombobox
                                :options="bomOptions"
                                placeholder="Select Bom"
                                label="Bom"
                                v-model:model-value="form.bom_id"
                                :error="form.errors.bom_id"
                            />
                            <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="form.status" />
                            <FormButton type="submit" label="Attach" :disabled="form.processing" :loading="form.processing" />
                        </form>
                    </Dialog>
                </div>
                <div>
                    <DataTable
                        v-model:model-value="filter"
                        :columns="columns"
                        :paginate-data="boms"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
