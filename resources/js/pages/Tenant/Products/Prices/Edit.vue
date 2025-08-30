<script setup lang="ts">
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Product, ProductPrice } from '@/types/Tenant/products';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    price: ProductPrice;
    options: {
        statuses: SwitchOption<ProductPrice['status']>[];
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

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.status ? status.value : !status.value))?.name);

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
    status: !!form.status,
});

const submit = () => form.put(route('products.prices.update', { tenant: tenant?.id || '', product: props.product.id, price: props.price.id }));

watch(
    () => config.status,
    (newVal) => {
        const value = props.options.statuses.find((status) => (newVal ? status.value === 1 : status.value === 0))?.value;

        if (value !== undefined) {
            form.status = value;
        }
    },
);
</script>

<template>
    <Head :title="price.currency" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Currency</Label>
                        <Select
                            :options="options.currencies"
                            placeholder="Select Currency"
                            v-model:model-value="form.currency"
                            trigger-class="w-full"
                        />
                        <p v-if="form.errors.currency" class="text-destructive">{{ form.errors.currency }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Amount</Label>
                        <Input type="number" placeholder="Enter Amount" v-model:model-value.number="form.amount" step=".01" min="0" />
                        <p v-if="form.errors.amount" class="text-destructive">{{ form.errors.amount }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label class="mb-1">Status</Label>
                        <div class="flex items-center space-x-2">
                            <Switch class="cursor-pointer" v-model:model-value="config.status" />
                            <Label>{{ statusDisplay }}</Label>
                        </div>
                        <p v-if="form.errors.status" class="text-destructive">{{ form.errors.status }}</p>
                    </div>
                    <div class="flex gap-2">
                        <Button type="submit" class="cursor-pointer" :disabled="form.processing">
                            <Loader v-if="form.processing" class="animate-spin" /> Update
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
