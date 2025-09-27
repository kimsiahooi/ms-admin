<script setup lang="ts">
import { BadgeVariants } from '@/components/shared/badge';
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormCombobox, FormSwitch } from '@/components/shared/custom/form';
import { SelectOption } from '@/components/shared/select';
import type { SwitchOption } from '@/components/shared/switch';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Status, StatusLabel, type Plant } from '@/types/Tenant/plants';
import { Department } from '@/types/Tenant/plants/departments';
import { StatusBadgeLabel } from '@/types/Tenant/plants/departments/tenant-users';
import { TenantUser } from '@/types/Tenant/tenant-users';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface PivotTenantUser {
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
    department_id: Department['id'];
    created_at: Date | null;
    updated_at: Date | null;
    user: TenantUser | null;
}

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    plant: Plant;
    department: Department;
    user: PivotTenantUser;
    options: {
        select: {
            users: SelectOption<TenantUser['id']>[];
        };
        switch: {
            statuses: SwitchOption[];
        };
    };
}>();

const { tenant } = useTenant();

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
        title: 'Departments',
        href: route('plants.departments.index', { tenant: tenant?.id || '', plant: props.plant.id }),
    },
    {
        title: props.department.name,
        href: '#',
    },
    {
        title: 'Users',
        href: route('plants.departments.users.index', { tenant: tenant?.id || '', plant: props.plant.id, department: props.department.id }),
    },
    {
        title: props.user.user?.name ?? '',
        href: '#',
    },
    {
        title: 'Edit',
        href: route('plants.departments.users.edit', {
            tenant: tenant?.id || '',
            plant: props.plant.id,
            department: props.department.id,
            user: props.user.id,
        }),
    },
]);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    user: props.user.user?.id,
    status: props.user.status.switch ?? undefined,
});

const submit = () =>
    form.put(
        route('plants.departments.users.update', {
            tenant: tenant?.id || '',
            plant: props.plant.id,
            department: props.department.id,
            user: props.user.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
</script>

<template>
    <Head :title="user.user?.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${user.user?.name}`">
                    <FormCombobox
                        :options="options.select.users"
                        placeholder="Select User"
                        label="User"
                        v-model:model-value="form.user"
                        :error="form.errors.user"
                    />
                    <FormSwitch
                        v-if="form.status !== undefined"
                        :label="statusDisplay"
                        :error="form.errors.status"
                        v-model:model-value="form.status"
                    />
                    <FormButton type="submit" :disabled="form.processing" label="Update" :loading="form.processing" />
                </Card>
            </form>
        </Layout>
    </AppLayout>
</template>
