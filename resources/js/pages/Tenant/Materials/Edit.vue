<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSelect, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import type { SelectOption } from '@/components/shared/select';
import type { SwitchOption } from '@/components/shared/switch';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Material, StatusBadgeLabel } from '@/types/Tenant/materials';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    material: Material;
    options: {
        statuses: SwitchOption<Material['status'], StatusBadgeLabel>[];
        unit_types: SelectOption<Material['unit_type']>[];
    };
}>();

const { tenant } = useTenant();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Materials',
        href: route('materials.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.material.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('materials.edit', { tenant: tenant?.id || '', material: props.material.id }),
    },
];

const statusDisplay = computed<StatusBadgeLabel>(() => props.options.statuses.find((status) => status.value === form.status)?.name ?? 'Active');

const form = useForm<{
    name: string;
    code: string;
    description: string;
    unit_type: Material['unit_type'];
    status: Material['status'];
}>({
    name: props.material.name,
    code: props.material.code,
    description: props.material.description || '',
    unit_type: props.material.unit_type,
    status: props.material.status,
});

const config = reactive({
    status: form.status === 'ACTIVE',
});

const submit = () => form.put(route('materials.update', { tenant: tenant?.id || '', material: props.material.id }));

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
    <Head :title="material.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${material.name}`">
                    <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                    <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                    <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                    <FormSelect label="Unit Type" :options="options.unit_types" v-model:model-value="form.unit_type" :error="form.errors.unit_type" />
                    <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                    <FormButton type="submit" :disabled="form.processing" label="Update" :loading="form.processing" />
                </Card>
            </form>
        </Layout>
    </AppLayout>
</template>
