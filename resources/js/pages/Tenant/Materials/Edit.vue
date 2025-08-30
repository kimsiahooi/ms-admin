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
import type { Material } from '@/types/Tenant/materials';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    material: Material;
    options: {
        statuses: SwitchOption<Material['status']>[];
        unit_types: SelectOption<Material['unit_type']>[];
    };
}>();

const { tenant } = useTenant();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Materials',
        href: route('materials.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.material.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('materials.edit', { tenant: tenant?.id || '', material: props.material.id }),
    },
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.status ? status.value : !status.value))?.name);

const form = useForm<{
    name: string;
    code: string;
    description: string;
    unit_type: Material['unit_type'];
    status: Material['status'];
}>({
    name: props.material.name,
    code: props.material.code,
    description: props.material.description || '',
    unit_type: props.material.unit_type,
    status: props.material.status,
});

const config = reactive({
    status: !!form.status,
});

const submit = () => form.put(route('materials.update', { tenant: tenant?.id || '', material: props.material.id }));

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
    <Head :title="material.name" />

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
                        <Label>Unit Type</Label>
                        <Select
                            :options="options.unit_types"
                            placeholder="Select Unit Type"
                            v-model:model-value="form.unit_type"
                            trigger-class="w-full"
                        />
                        <p v-if="form.errors.unit_type" class="text-destructive">{{ form.errors.unit_type }}</p>
                    </div>
                    <div class="grid w-full items-center gap-1.5">
                        <Label class="mb-1">Status</Label>
                        <div class="flex items-center space-x-2">
                            <Switch class="cursor-pointer" v-model:model-value="config.status" />
                            <Label>{{ statusDisplay }}</Label>
                        </div>
                        <p v-if="form.errors.status" class="text-destructive">{{ form.errors.status }}</p>
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
