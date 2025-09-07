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
import { Status, StatusLabel, type ProductBom } from '@/types/Tenant/products/boms';
import { Head, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import slug from 'slug';
import { computed, ref, watch } from 'vue';

type PartialMaterial = Pick<Material, 'id' | 'name' | 'unit_type' | 'unit_type_label'>;

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    product: Product;
    materials: PartialMaterial[];
    options: {
        statuses: SelectOption<ProductBom['status']['value']>[];
        switch_statuses: SwitchOption[];
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
        title: 'Create',
        href: route('products.boms.create', { tenant: tenant?.id || '', product: props.product.id }),
    },
];

const materialConfig: Omit<MaterialConfig<PartialMaterial>, 'key'> = {
    data: null,
    quantity: '',
    unit_type: '',
};

const selectedMaterials = ref<MaterialConfig<PartialMaterial>[]>([{ ...materialConfig, key: uuid() }]);

const defaultStatus = computed<(typeof props.options.switch_statuses)[number]['value']>(
    () => !!props.options.switch_statuses.find((status) => status.is_default)?.value,
);

const statusDisplay = computed(
    () => props.options.switch_statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const materialOptions = computed<SelectOption<PartialMaterial>[]>(() =>
    props.materials.map((material) => ({ name: material.name, value: material })),
);

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
    status: (typeof props.options.switch_statuses)[number]['value'];
}>({
    name: '',
    code: '',
    description: '',
    materials: [],
    status: defaultStatus.value,
});

const addMeterial = () => {
    selectedMaterials.value = [...selectedMaterials.value, { ...materialConfig, key: uuid() }];
};

const removeMaterial = (key: string) => {
    selectedMaterials.value = selectedMaterials.value.filter((material) => material.key !== key);
};

const submit = () =>
    form.post(route('products.boms.store', { tenant: tenant?.id || '', product: props.product.id }), {
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
</script>

<template>
    <Head title="Create Product Bom" />

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
                    <FormSwitch :label="statusDisplay" :error="form.errors.status" v-model:model-value="form.status" />
                    <FormButton type="submit" :disabled="form.processing" :loading="form.processing" />
                </form>
            </div>
        </Layout>
    </AppLayout>
</template>
