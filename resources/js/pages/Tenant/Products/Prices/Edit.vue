<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSelect, FormSwitch } from '@/components/shared/custom/form';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Product } from '@/types/Tenant/products';
import type { ProductPrice, StatusBadgeLabel } from '@/types/Tenant/products/prices';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    price: ProductPrice;
    options: {
        statuses: SwitchOption<ProductPrice['status'], StatusBadgeLabel>[];
        currencies: SelectOption<ProductPrice['currency']>[];
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
        title: 'Prices',
        href: route('products.prices.index', { tenant: tenant?.id || '', product: props.product.id }),
    },
    {
        title: props.price.currency,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('products.prices.edit', { tenant: tenant?.id || '', product: props.product.id, price: props.price.id }),
    },
];

const statusDisplay = computed<StatusBadgeLabel>(() => props.options.statuses.find((status) => status.value === form.status)?.name ?? 'Active');

const form = useForm<{
    currency: string;
    amount: number | '';
    status: ProductPrice['status'];
}>({
    currency: props.price.currency,
    amount: +props.price.amount,
    status: props.price.status,
});

const config = reactive({
    status: form.status === 'ACTIVE',
});

const submit = () => form.put(route('products.prices.update', { tenant: tenant?.id || '', product: props.product.id, price: props.price.id }));

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
    <Head :title="price.currency" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <form @submit.prevent="submit">
                    <Card :title="`Edit ${price.currency}`">
                        <FormSelect
                            label="Currency"
                            :options="options.currencies"
                            v-model:model-value="form.currency"
                            :error="form.errors.currency"
                        />
                        <FormInput label="Amount" type="number" :error="form.errors.amount" v-model:model-value="form.amount" step=".01" min="0.01" />
                        <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                        <FormButton type="submit" :disabled="form.processing" :loading="form.processing" />
                    </Card>
                </form>
            </div>
        </Layout>
    </AppLayout>
</template>
