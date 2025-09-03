<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSelect, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Machine } from '@/types/Tenant/machines';
import type { Product } from '@/types/Tenant/products';
import type { ProductPresetWithMachine, StatusLabel } from '@/types/Tenant/products/presets';
import { Head, useForm } from '@inertiajs/vue3';
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
        shelf_life_types: SelectOption<ProductPresetWithMachine['shelf_life_type']>[];
        statuses: SwitchOption<ProductPresetWithMachine['status'], StatusLabel>[];
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

const statusDisplay = computed<StatusLabel>(() => props.options.statuses.find((status) => status.value === form.status)?.name ?? 'Active');

const form = useForm<{
    machine_id: Machine['id'] | '';
    name: string;
    code: string;
    description: string;
    cavity_quantity: number | '';
    cavity_type: ProductPresetWithMachine['cavity_type'] | '';
    cycle_time: number | '';
    cycle_time_type: ProductPresetWithMachine['cycle_time_type'] | '';
    shelf_life_duration: string;
    shelf_life_type: ProductPresetWithMachine['shelf_life_type'] | '';
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
    shelf_life_duration: props.preset.shelf_life_duration || '',
    shelf_life_type: props.preset.shelf_life_type || '',
    status: props.preset.status,
});

const config = reactive({
    status: form.status === 'ACTIVE',
});

const submit = () => form.put(route('products.presets.update', { tenant: tenant?.id || '', product: props.product.id, preset: props.preset.id }));

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
    <Head :title="preset.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <form @submit.prevent="submit">
                    <Card :title="`Edit ${preset.name}`">
                        <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                        <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                        <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                        <FormSelect
                            label="Machine"
                            :options="options.machines"
                            v-model:model-value="form.machine_id"
                            :error="form.errors.machine_id"
                        />
                        <FormInput
                            label="Cavity Quantity"
                            :error="form.errors.cavity_quantity"
                            v-model:model-value="form.cavity_quantity"
                            type="number"
                            min="0"
                            step=".01"
                        />
                        <FormSelect
                            label="Cavity Type"
                            :options="options.cavity_types"
                            v-model:model-value="form.cavity_type"
                            :error="form.errors.cavity_type"
                        />
                        <FormInput
                            label="Cycle Time"
                            :error="form.errors.cycle_time"
                            v-model:model-value="form.cycle_time"
                            type="number"
                            min="0"
                            step=".01"
                        />
                        <FormSelect
                            label="Cycle Time Type"
                            :options="options.cycle_time_types"
                            v-model:model-value="form.cycle_time_type"
                            :error="form.errors.cycle_time_type"
                        />
                        <FormInput
                            label="Shelf Life Duration"
                            :error="form.errors.shelf_life_duration"
                            v-model:model-value="form.shelf_life_duration"
                            type="number"
                            min="0"
                            step=".01"
                        />
                        <FormSelect
                            label="Shelf Life Type"
                            :options="options.shelf_life_types"
                            v-model:model-value="form.shelf_life_type"
                            :error="form.errors.shelf_life_type"
                        />
                        <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                        <FormButton type="submit" label="Update" :disabled="form.processing" :loading="form.processing" />
                    </Card>
                </form>
            </div>
        </Layout>
    </AppLayout>
</template>
