<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import type { SelectOption } from '@/components/shared/select';
import type { SwitchOption } from '@/components/shared/switch';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Status, StatusLabel, type Machine } from '@/types/Tenant/machines';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    machine: Machine;
    options: {
        statuses: SelectOption<Machine['status']['value']>[];
        switch_statuses: SwitchOption[];
    };
}>();

const { tenant } = useTenant();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Machines',
        href: route('machines.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.machine.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('machines.edit', { tenant: tenant?.id || '', machine: props.machine.id }),
    },
];

const statusDisplay = computed(
    () => props.options.switch_statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    name: props.machine.name,
    code: props.machine.code,
    description: props.machine.description || '',
    status: props.machine.status.switch ?? undefined,
});

const submit = () =>
    form.put(route('machines.update', { tenant: tenant?.id || '', machine: props.machine.id }), {
        preserveScroll: true,
        preserveState: true,
    });
</script>

<template>
    <Head :title="machine.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${machine.name}`">
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
