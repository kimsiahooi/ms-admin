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
import { Operation } from '@/types/Tenant/plants/operations';
import { Task } from '@/types/Tenant/plants/operations/tasks';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    plant: Plant;
    operation: Operation;
    task: Task;
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
        title: 'Operations',
        href: route('plants.operations.index', { tenant: tenant?.id || '', plant: props.plant.id }),
    },
    {
        title: props.operation.name,
        href: '#',
    },
    {
        title: 'Tasks',
        href: route('plants.operations.tasks.index', { tenant: tenant?.id || '', plant: props.plant.id, operation: props.operation.id }),
    },
    {
        title: props.task.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('plants.operations.tasks.edit', {
            tenant: tenant?.id || '',
            plant: props.plant.id,
            operation: props.operation.id,
            task: props.task.id,
        }),
    },
]);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    name: props.task.name,
    code: props.task.code,
    description: props.task.description || '',
    status: props.task.status.switch ?? undefined,
});

const submit = () =>
    form.put(
        route('plants.operations.tasks.update', {
            tenant: tenant?.id || '',
            plant: props.plant.id,
            operation: props.operation.id,
            task: props.task.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
</script>

<template>
    <Head :title="task.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${task.name}`">
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
