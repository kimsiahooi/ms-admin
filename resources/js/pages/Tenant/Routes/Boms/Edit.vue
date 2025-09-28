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
import { Product, ProductWithBoms } from '@/types/Tenant/products';
import { ProductBom } from '@/types/Tenant/products/boms';
import { Route } from '@/types/Tenant/routes';
import { Status, StatusBadgeLabel, StatusLabel } from '@/types/Tenant/routes/boms';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

interface ProductBomWithProduct extends ProductBom {
    product: Product | null;
}

interface PivotProductBom {
    readonly id: string;
    status: {
        value: Status;
        badge?: {
            name: StatusBadgeLabel | null;
            variant: BadgeVariants['variant'];
        } | null;
        switch?: boolean | null;
    };
    bom_id: ProductBom['id'];
    route_id: Route['id'];
    bom: ProductBomWithProduct | null;
    created_at: Date | null;
    updated_at: Date | null;
}

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    route: Route;
    bom: PivotProductBom;
    products: ProductWithBoms[];
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
        title: 'Routes',
        href: route('routes.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.route.name,
        href: '#',
    },
    {
        title: 'Boms',
        href: route('routes.boms.index', { tenant: tenant?.id || '', route: props.route.id }),
    },
    {
        title: props.bom.bom?.name ?? '',
        href: '#',
    },
    {
        title: 'Edit',
        href: route('routes.boms.edit', { tenant: tenant?.id || '', route: props.route.id, bom: props.bom.bom_id }),
    },
]);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const productOptions = computed<SelectOption<ProductWithBoms['id']>[]>(() =>
    props.products.map((product) => ({ name: product.name, value: product.id })),
);

const bomOptions = computed<SelectOption<ProductWithBoms['boms'][number]['id']>[]>(
    () => props.products.find((product) => product.id === form.product_id)?.boms.map((bom) => ({ name: bom.name, value: bom.id })) ?? [],
);

const selectedProduct = computed(() => props.products.find((product) => product.id === props.bom.bom?.product_id));

const form = useForm({
    product_id: selectedProduct.value?.id,
    bom_id: selectedProduct.value?.boms.find((bom) => bom.id === props.bom.bom_id)?.id,
    status: props.bom.status.switch ?? undefined,
});

const submit = () =>
    form.put(
        route('routes.boms.update', {
            tenant: tenant?.id || '',
            route: props.route.id,
            bom: props.bom.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
        },
    );

watch(
    () => form.product_id,
    () => {
        form.bom_id = undefined;
    },
);
</script>

<template>
    <Head :title="bom.bom?.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${bom.bom?.name}`">
                    <FormCombobox
                        :options="productOptions"
                        placeholder="Select Product"
                        label="Product"
                        v-model:model-value="form.product_id"
                        :error="form.errors.product_id"
                    />
                    <FormCombobox
                        :options="bomOptions"
                        placeholder="Select Bom"
                        label="Bom"
                        v-model:model-value="form.bom_id"
                        :error="form.errors.bom_id"
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
