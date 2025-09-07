<script setup lang="ts">
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import type { SelectOption } from '@/components/shared/select';
import type { SwitchOption } from '@/components/shared/switch';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { MaterialSelect, type MaterialConfig } from '@/components/view/Tenant/Products/Boms';
import { useTenant } from '@/composables/useTenant';
import { useUuid } from '@/composables/useUuid';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Material } from '@/types/Tenant/materials';
import type { Product } from '@/types/Tenant/products';
import { Status, StatusLabel, type ProductBomWithMaterials, type StatusBadgeLabel } from '@/types/Tenant/products/boms';
import { Head, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import slug from 'slug';
import { computed, reactive, ref, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    bom: ProductBomWithMaterials;
    materials: Material[];
    options: {
        statuses: SwitchOption<ProductBomWithMaterials['status']['value'], StatusBadgeLabel>[];
        unit_types: SelectOption<Material['unit_type']>[];
    };
}>();

const { tenant } = useTenant();
const { uuid } = useUuid();

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
        title: 'Boms',
        href: route('products.boms.index', { tenant: tenant?.id || '', product: props.product.id }),
    },
    {
        title: props.bom.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('products.boms.edit', { tenant: tenant?.id || '', product: props.product.id, bom: props.bom.id }),
    },
];

const materialConfig: Omit<MaterialConfig, 'key'> = {
    data: null,
    quantity: '',
    unit_type: '',
};

const selectedMaterials = ref<MaterialConfig[]>(
    props.bom.materials.length
        ? props.bom.materials.map((material) => ({
              key: uuid(),
              data: material,
              quantity: +material.pivot.quantity,
              unit_type: material.pivot.unit_type,
          }))
        : [{ ...materialConfig, key: uuid() }],
);

const statusDisplay = computed<StatusBadgeLabel>(
    () => props.options.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.ACTIVE],
);

const materialOptions = computed<SelectOption<Material>[]>(() => props.materials.map((material) => ({ name: material.name, value: material })));

const form = useForm<{
    name: string;
    code: string;
    description: string;
    materials: {
        key: string;
        id: Material['id'] | '';
        quantity: number | '';
        unit_type: Material['unit_type'] | '';
    }[];
    status: ProductBomWithMaterials['status']['value'];
}>({
    name: props.bom.name,
    code: props.bom.code,
    description: props.bom.description || '',
    materials: [],
    status: props.bom.status.value,
});

const config = reactive({
    status: form.status === Status.ACTIVE,
});

const addMeterial = () => {
    selectedMaterials.value = [...selectedMaterials.value, { ...materialConfig, key: uuid() }];
};

const removeMaterial = (key: string) => {
    selectedMaterials.value = selectedMaterials.value.filter((material) => material.key !== key);
};

const submit = () =>
    form.put(route('products.boms.update', { tenant: tenant?.id || '', product: props.product.id, bom: props.bom.id }), {
        preserveScroll: true,
        preserveState: true,
    });

watch(
    selectedMaterials,
    (newVal) => {
        form.materials = newVal.map((material) => ({
            key: material.key,
            id: material.data?.id || '',
            quantity: material.quantity,
            unit_type: material.unit_type,
        }));
    },
    { immediate: true, deep: true },
);

watch(
    () => form.name,
    (newVal) => {
        form.code = slug(newVal);
    },
);

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
    <Head :title="`Edit ${props.bom.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="space-y-3">
                <form @submit.prevent="submit" class="space-y-4">
                    <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                    <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                    <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                    <div class="space-y-3">
                        <div>
                            <Label class="mb-1">Materials:</Label>
                        </div>
                        <div class="space-y-3">
                            <div v-for="(material, index) in selectedMaterials" :key="material.key" class="space-y-1">
                                <MaterialSelect
                                    :material-options="materialOptions"
                                    :unit-type-options="options.unit_types"
                                    :total-selected="selectedMaterials.length"
                                    v-model:model-value="selectedMaterials[index]"
                                    :curr-index="index"
                                    @remove="removeMaterial"
                                />
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <Button type="button" class="cursor-pointer" variant="outline" @click="addMeterial"><Plus /> Add Material</Button>
                        </div>
                    </div>
                    <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="config.status" />
                    <FormButton type="submit" label="Update" :disabled="form.processing" :loading="form.processing" />
                </form>
            </div>
        </Layout>
    </AppLayout>
</template>
