<script setup lang="ts">
import { ErrorMessages } from '@/components/shared/error';
import type { SelectOption } from '@/components/shared/select/types';
import type { SwitchOption } from '@/components/shared/switch/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { MaterialSelect, type MaterialConfig } from '@/components/view/Tenant/Products/Boms';
import { useTenant } from '@/composables/useTenant';
import { useUuid } from '@/composables/useUuid';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Material } from '@/types/Tenant/materials';
import type { Product, ProductBomWithMaterials } from '@/types/Tenant/products';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader, Plus } from 'lucide-vue-next';
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
        statuses: SwitchOption<ProductBomWithMaterials['status']>[];
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
        ? props.bom.materials
              .filter((bomMaterial) => props.materials.some((material) => material.id === bomMaterial.id))
              .map((material) => ({ key: uuid(), data: material, quantity: +material.pivot.quantity, unit_type: material.pivot.unit_type }))
        : [{ ...materialConfig, key: uuid() }],
);

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.status ? status.value : !status.value))?.name);
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
    status: ProductBomWithMaterials['status'];
}>({
    name: props.bom.name,
    code: props.bom.code,
    description: props.bom.description || '',
    materials: [],
    status: props.bom.status,
});

const config = reactive({
    status: !!form.status,
});

const addMeterial = () => {
    selectedMaterials.value = [...selectedMaterials.value, { ...materialConfig, key: uuid() }];
};

const removeMaterial = (key: string) => {
    selectedMaterials.value = selectedMaterials.value.filter((material) => material.key !== key);
};

const submit = () => form.put(route('products.boms.update', { tenant: tenant?.id || '', product: props.product.id, bom: props.bom.id }));

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
        const value = props.options.statuses.find((status) => (newVal ? status.value === 1 : status.value === 0))?.value;

        if (value !== undefined) {
            form.status = value;
        }
    },
);
</script>

<template>
    <Head :title="`Edit ${props.bom.name}`" />

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
                    <div class="space-y-3">
                        <div>
                            <Label class="mb-1">Materials</Label>
                        </div>
                        <div class="space-y-3">
                            <div v-for="(material, index) in selectedMaterials" :key="material.key" class="space-y-1">
                                <MaterialSelect
                                    :material-options="materialOptions"
                                    :unit-type-options="options.unit_types"
                                    :total-selected="selectedMaterials.length"
                                    v-model:model-value="selectedMaterials[index]"
                                    @remove="removeMaterial"
                                />
                                <ErrorMessages :error-key="`materials.${index}`" />
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <Button type="button" class="cursor-pointer" variant="outline" @click="addMeterial"><Plus /> Add Material</Button>
                        </div>
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
