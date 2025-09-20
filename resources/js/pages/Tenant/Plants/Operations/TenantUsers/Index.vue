<script setup lang="ts">
import { BadgeVariants, StatusBadge } from '@/components/shared/badge';
import { ActionButton } from '@/components/shared/custom/action';
import { Layout } from '@/components/shared/custom/container';
import { FilterCard, FilterInput, FilterSelect } from '@/components/shared/custom/filter';
import { FormButton, FormCombobox, FormSwitch } from '@/components/shared/custom/form';
import { DeleteDialog, Dialog } from '@/components/shared/dialog';
import type { PaginateData } from '@/components/shared/pagination';
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
import type { Plant } from '@/types/Tenant/plants';
import { Operation, Status, StatusLabel } from '@/types/Tenant/plants/operations';
import { StatusBadgeLabel } from '@/types/Tenant/plants/operations/tenant-users';
import { TenantUser } from '@/types/Tenant/tenant-users';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { pickBy } from 'lodash-es';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, h, reactive } from 'vue';

type PivotTenantUser = TenantUser & {
    pivot: {
        readonly id: string;
        status: {
            value: Status;
            badge?: {
                name: StatusBadgeLabel | null;
                variant: BadgeVariants['variant'];
            } | null;
            switch?: boolean | null;
        };
        tuser_id: TenantUser['id'];
        operation_id: Operation['id'];
        created_at: Date | null;
        updated_at: Date | null;
    };
};

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    plant: Plant;
    operation: Operation;
    users: PaginateData<PivotTenantUser[]>;
    options: {
        select: {
            statuses: SelectOption<PivotTenantUser['pivot']['status']['value']>[];
            users: SelectOption<TenantUser['id']>[];
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
        title: 'Users',
        href: route('plants.operations.users.index', { tenant: tenant?.id || '', plant: props.plant.id, operation: props.operation.id }),
    },
]);

const search = () =>
    router.visit(
        route('plants.operations.users.index', {
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

const columns: ColumnDef<PivotTenantUser>[] = [
    {
        accessorKey: 'actions',
        header: () => h('div', null, 'Actions'),
        cell: ({ row }) => {
            const user = row.original;

            return h('div', { class: 'flex items-center gap-2' }, [
                h(StatusSwitch, {
                    value: user.pivot.status.switch,
                    method: 'put',
                    href: route('plants.operations.users.toggleStatus', {
                        tenant: tenant?.id || '',
                        plant: props.plant.id,
                        operation: props.operation.id,
                        user: user.pivot.id,
                    }),
                }),
                h(ActionButton, {
                    text: 'Edit',
                    href: route('plants.operations.users.edit', {
                        tenant: tenant?.id || '',
                        plant: props.plant.id,
                        operation: props.operation.id,
                        user: user.pivot.id,
                    }),
                    icon: Pencil,
                }),
                h(
                    DeleteDialog,
                    {
                        title: `Detach ${user.name}`,
                        route: route('plants.operations.users.destroy', {
                            tenant: tenant?.id || '',
                            plant: props.plant.id,
                            operation: props.operation.id,
                            user: user.pivot.id,
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
        accessorKey: 'user name',
        header: () => h('div', null, 'User Name'),
        cell: ({ row }) => {
            const { name } = row.original;
            return h('div', null, name);
        },
    },
    {
        accessorKey: 'user email',
        header: () => h('div', null, 'User Email'),
        cell: ({ row }) => {
            const { email } = row.original;
            return h('div', null, email);
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

const columnVisibility: VisibilityState<PivotTenantUser> = {
    id: false,
};

const defaultStatus = computed<(typeof props.options.switch.statuses)[number]['value']>(
    () => !!props.options.switch.statuses.find((status) => status.is_default)?.value,
);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    user: '',
    status: defaultStatus.value,
});

const submit = () =>
    form.post(route('plants.operations.users.store', { tenant: tenant?.id || '', plant: props.plant.id, operation: props.operation.id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            setting.create.dialogIsOpen = false;
        },
    });
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <FilterCard @search="search" @reset="reset">
                    <FilterInput label="Name" placeholder="Search ID, Name, Email" v-model:model-value="filter.search" />
                    <FilterSelect
                        label="Status"
                        placeholder="Select Status"
                        :options="options.select.statuses"
                        v-model:model-value="filter.status"
                        multiple
                    />
                </FilterCard>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Dialog title="Attach User" v-model:open="setting.create.dialogIsOpen" trigger-label="Attach">
                        <form @submit.prevent="submit" class="space-y-4">
                            <FormCombobox
                                :options="options.select.users"
                                placeholder="Select User"
                                label="User"
                                v-model:model-value="form.user"
                                :error="form.errors.user"
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
                        :paginate-data="users"
                        :column-visibility="columnVisibility"
                        :entry-options="entryOptions"
                        @search="search"
                    />
                </div>
            </div>
        </Layout>
    </AppLayout>
</template>
