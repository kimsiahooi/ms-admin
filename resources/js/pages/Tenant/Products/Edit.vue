<script setup lang="ts">
import { ErrorMessages } from '@/components/shared/error';
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Material } from '@/types/Tenant/materials';
import type { Product, ProductPrice, ProductWithPrices } from '@/types/Tenant/products';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader, Minus, Plus } from 'lucide-vue-next';
import { v4 as uuidv4 } from 'uuid';
import { computed } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: ProductWithPrices;
    options: {
        statuses: SwitchOption<number>[];
        materials: SelectOption<Material['id']>[];
        currencies: SelectOption<ProductPrice['currency']>[];
        shelf_life_types: SelectOption<Product['shelf_life_type']>[];
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
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.is_active ? status.value : !status.value))?.name);

const form = useForm<{
    name: string;
    code: string;
    description: string;
    shelf_life_duration: string;
    shelf_life_type: Product['shelf_life_type'] | '';
    prices: {
        id: string;
        currency: ProductPrice['currency'] | '';
        amount: string;
    }[];
    is_active: boolean;
}>({
    name: props.product.name,
    code: props.product.code,
    description: props.product.description || '',
    shelf_life_duration: props.product.shelf_life_duration || '',
    shelf_life_type: props.product.shelf_life_type || '',
    prices: props.product.prices.length
        ? props.product.prices.map((price) => ({ id: price.id, currency: price.currency, amount: price.amount }))
        : [{ id: uuidv4(), currency: '', amount: '' }],
    is_active: true,
});

const priceHandler = (
    type: 'add' | 'remove',
    price: {
        id: string;
        currency: ProductPrice['currency'] | '';
        amount: string;
    },
) => {
    if (type === 'add') {
        form.prices = [...form.prices, { id: uuidv4(), currency: '', amount: '' }];
    } else if (type === 'remove') {
        form.prices = form.prices.filter((p) => p.id !== price.id);
    }
};

const submit = () => form.put(route('products.update', { tenant: tenant?.id || '', product: props.product.id }));
</script>

<template>
    <Head :title="product.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-3">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Name</Label>
                        <Input type="text" placeholder="Enter Name" v-model:model-value="form.name" />
                        <p v-if="form.errors.name" class="text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Code</Label>
                        <Input type="text" placeholder="Enter Code" v-model:model-value="form.code" />
                        <p v-if="form.errors.code" class="text-destructive">{{ form.errors.code }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Description</Label>
                        <Textarea placeholder="Enter Description" v-model:model-value="form.description" />
                        <p v-if="form.errors.description" class="text-destructive">{{ form.errors.description }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <div class="grid gap-1.5 md:grid-cols-2">
                            <div class="grid gap-1.5">
                                <Label>Shelf Life Duration</Label>
                                <Input
                                    type="number"
                                    placeholder="Enter Shelf Life Duration"
                                    v-model:model-value="form.shelf_life_duration"
                                    min="0.01"
                                    step=".01"
                                    class="w-full"
                                />
                            </div>
                            <div class="grid gap-1.5">
                                <Label>Shelf Life Type</Label>
                                <Select
                                    :options="options.shelf_life_types"
                                    placeholder="Select Shelf Life Type"
                                    v-model:model-value="form.shelf_life_type"
                                    trigger-class="w-full"
                                />
                            </div>
                        </div>
                        <p v-if="form.errors.shelf_life_duration" class="text-destructive">{{ form.errors.shelf_life_duration }}</p>
                        <p v-if="form.errors.shelf_life_type" class="text-destructive">{{ form.errors.shelf_life_type }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Unit price</Label>
                        <div v-for="(price, index) in form.prices" :key="price.id">
                            <div class="flex items-center gap-2">
                                <div class="flex-1">
                                    <Select
                                        :options="options.currencies"
                                        placeholder="Select Currency"
                                        v-model:model-value="price.currency"
                                        trigger-class="w-full"
                                    />
                                </div>
                                <div class="flex-1">
                                    <Input
                                        type="number"
                                        step=".01"
                                        min="0"
                                        placeholder="Enter Unit Price"
                                        v-model:model-value="price.amount"
                                        class="w-full"
                                    />
                                </div>
                                <div>
                                    <Button
                                        type="button"
                                        size="icon"
                                        class="cursor-pointer"
                                        :variant="!index ? 'default' : 'destructive'"
                                        @click="priceHandler(!index ? 'add' : 'remove', price)"
                                    >
                                        <Plus v-if="!index" />
                                        <Minus v-else />
                                    </Button>
                                </div>
                            </div>
                            <ErrorMessages :error-key="`prices.${index}`" />
                        </div>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label class="mb-1">Status</Label>
                        <div class="flex items-center space-x-2">
                            <Switch class="cursor-pointer" v-model:model-value="form.is_active" />
                            <Label>{{ statusDisplay }}</Label>
                        </div>
                        <p v-if="form.errors.is_active" class="text-destructive">{{ form.errors.is_active }}</p>
                    </div>
                    <div>
                        <Button type="submit" class="cursor-pointer" :disabled="form.processing">
                            <Loader v-if="form.processing" class="animate-spin" /> Update
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
