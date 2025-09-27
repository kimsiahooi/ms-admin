<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import type { SwitchOption } from '@/components/shared/switch';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Status, StatusLabel, type Plant } from '@/types/Tenant/plants';
import { Department } from '@/types/Tenant/plants/departments';
import { Operation } from '@/types/Tenant/plants/departments/operations';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    plant: Plant;
    department: Department;
    operation: Operation;
    options: {
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
        title: 'Operations',
        href: route('plants.departments.operations.index', { tenant: tenant?.id || '', plant: props.plant.id, department: props.department.id }),
    },
    {
        title: props.operation.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('plants.departments.operations.edit', {
            tenant: tenant?.id || '',
            plant: props.plant.id,
            department: props.department.id,
            operation: props.operation.id,
        }),
    },
]);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    name: props.operation.name,
    code: props.operation.code,
    description: props.operation.description || '',
    status: props.operation.status.switch ?? undefined,
});

const submit = () =>
    form.put(
        route('plants.departments.operations.update', {
            tenant: tenant?.id || '',
            plant: props.plant.id,
            department: props.department.id,
            operation: props.operation.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
</script>

<template>
    <Head :title="operation.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${operation.name}`">
                    <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                    <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                    <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
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
