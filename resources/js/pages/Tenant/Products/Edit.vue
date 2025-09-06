<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import type { SwitchOption } from '@/components/shared/switch/types';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Product, StatusBadgeLabel } from '@/types/Tenant/products';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    options: {
        statuses: SwitchOption<Product['status'], StatusBadgeLabel>[];
    };
}>();

const { tenant } = useTenant();

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
        title: 'Edit',
        href: route('products.edit', { tenant: tenant?.id || '', product: props.product.id }),
    },
];

const statusDisplay = computed<StatusBadgeLabel>(() => props.options.statuses.find((status) => status.value === form.status)?.name ?? 'Active');

const form = useForm<{
    name: string;
    code: string;
    description: string;
    status: Product['status'];
}>({
    name: props.product.name,
    code: props.product.code,
    description: props.product.description || '',
    status: props.product.status,
});

const config = reactive({
    status: form.status === 'ACTIVE',
});

const submit = () => form.put(route('products.update', { tenant: tenant?.id || '', product: props.product.id }));

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
    <Head :title="product.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${product.name}`">
                    <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                    <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                    <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                    <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                    <FormButton type="submit" :disabled="form.processing" label="Update" :loading="form.processing" />
                </Card>
            </form>
        </Layout>
    </AppLayout>
</template>
