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
import { Status, StatusLabel, type Company } from '@/types/Tenant/companies';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    company: Company;
    options: {
        statuses: SelectOption<Company['status']['value']>[];
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
        title: 'Companies',
        href: route('companies.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.company.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('companies.edit', { tenant: tenant?.id || '', company: props.company.id }),
    },
];

const statusDisplay = computed(
    () => props.options.switch_statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm({
    name: props.company.name,
    code: props.company.code,
    description: props.company.description || '',
    status: props.company.status.switch ?? undefined,
});

const submit = () =>
    form.put(route('companies.update', { tenant: tenant?.id || '', company: props.company.id }), {
        preserveScroll: true,
        preserveState: true,
    });
</script>

<template>
    <Head :title="company.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${company.name}`">
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
