<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch } from '@/components/shared/custom/form';
import type { SwitchOption } from '@/components/shared/switch';

import AppLayout from '@/layouts/Admin/AppLayout.vue';
import AppMainLayout from '@/layouts/Admin/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Status, StatusLabel, type StatusBadgeLabel, type Tenant } from '@/types/Admin/tenants';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    tenant: Tenant;
    options: {
        statuses: SwitchOption<Tenant['status'], StatusBadgeLabel>[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('admin.dashboard'),
    },
    {
        title: 'Tenants',
        href: route('admin.tenants.index'),
    },
    {
        title: props.tenant.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('admin.tenants.edit', { tenant: props.tenant.id }),
    },
];

const statusDisplay = computed<StatusBadgeLabel>(
    () => props.options.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.ACTIVE],
);

const form = useForm({
    name: props.tenant.name,
    status: props.tenant.status,
});

const config = reactive({
    status: form.status === Status.ACTIVE,
});

const submit = () =>
    form.put(route('admin.tenants.update', { tenant: props.tenant.id }), {
        preserveScroll: true,
        preserveState: true,
    });

watch(
    () => config.status,
    (newVal) => {
        const value = props.options.statuses.find((status) => (newVal ? status.value === Status.ACTIVE : status.value === Status.INACTIVE))?.value;

        if (value !== undefined) {
            form.status = value;
        }
    },
);
</script>

<template>
    <Head :title="props.tenant.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${tenant.name}`">
                    <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                    <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                    <FormButton type="submit" :disabled="form.processing" label="Update" :loading="form.processing" />
                </Card>
            </form>
        </Layout>
    </AppLayout>
</template>
