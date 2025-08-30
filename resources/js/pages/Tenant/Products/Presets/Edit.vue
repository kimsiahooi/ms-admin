<script setup lang="ts">
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
import type { Machine } from '@/types/Tenant/machines';
import type { Product, ProductPresetWithMachine } from '@/types/Tenant/products';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    preset: ProductPresetWithMachine;
    options: {
        machines: SelectOption<Machine['id']>[];
        cavity_types: SelectOption<ProductPresetWithMachine['cavity_type']>[];
        cycle_time_types: SelectOption<ProductPresetWithMachine['cycle_time_type']>[];
        statuses: SwitchOption<ProductPresetWithMachine['status']>[];
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
        title: 'Presets',
        href: route('products.presets.index', { tenant: tenant?.id || '', product: props.product.id }),
    },
    {
        title: 'Edit',
        href: route('products.presets.edit', { tenant: tenant?.id || '', product: props.product.id, preset: props.preset.id }),
    },
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.status ? status.value : !status.value))?.name);

const form = useForm<{
    machine_id: Machine['id'] | '';
    name: string;
    code: string;
    description: string;
    cavity_quantity: number | '';
    cavity_type: ProductPresetWithMachine['cavity_type'] | '';
    cycle_time: number | '';
    cycle_time_type: ProductPresetWithMachine['cycle_time_type'] | '';
    status: ProductPresetWithMachine['status'];
}>({
    machine_id: props.preset.machine?.id || '',
    name: props.preset.name,
    code: props.preset.code,
    description: props.preset.description || '',
    cavity_quantity: +props.preset.cavity_quantity,
    cavity_type: props.preset.cavity_type,
    cycle_time: +props.preset.cycle_time,
    cycle_time_type: props.preset.cycle_time_type,
    status: props.preset.status,
});

const config = reactive({
    status: !!form.status,
});

const submit = () => form.put(route('products.presets.update', { tenant: tenant?.id || '', product: props.product.id, preset: props.preset.id }));

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
    <Head :title="props.preset.name" />

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
                        <Label>Machine</Label>
                        <Select
                            :options="options.machines"
                            placeholder="Select Machine"
                            v-model:model-value="form.machine_id"
                            trigger-class="w-full"
                        />
                        <p v-if="form.errors.machine_id" class="text-destructive">{{ form.errors.machine_id }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Cavity Quantity</Label>
                        <Input
                            type="number"
                            placeholder="Enter Cavity Quantity"
                            v-model:model-value.number="form.cavity_quantity"
                            min="0"
                            step=".01"
                        />
                        <p v-if="form.errors.cavity_quantity" class="text-destructive">{{ form.errors.cavity_quantity }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Cavity Type</Label>
                        <Select
                            :options="options.cavity_types"
                            placeholder="Select Cavity Type"
                            v-model:model-value="form.cavity_type"
                            trigger-class="w-full"
                        />
                        <p v-if="form.errors.cavity_type" class="text-destructive">{{ form.errors.cavity_type }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Cycle Time</Label>
                        <Input type="number" placeholder="Enter Cycle Time" v-model:model-value.number="form.cycle_time" min="0" step=".01" />
                        <p v-if="form.errors.cycle_time" class="text-destructive">{{ form.errors.cycle_time }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label>Cycle Time Type</Label>
                        <Select
                            :options="options.cycle_time_types"
                            placeholder="Select Cycle Time Type"
                            v-model:model-value="form.cycle_time_type"
                            trigger-class="w-full"
                        />
                        <p v-if="form.errors.cycle_time_type" class="text-destructive">{{ form.errors.cycle_time_type }}</p>
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
