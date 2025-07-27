<script setup lang="ts">
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
import { Head, useForm } from '@inertiajs/vue3';
import { Loader } from 'lucide-vue-next';
import { computed } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    machine: Machine;
    options: {
        statuses: SwitchOption<number>[];
    };
}>();

const { tenant } = useTenant();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Machines',
        href: route('machines.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.machine.name,
        href: '#',
    },
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.is_active ? status.value : !status.value))?.name);

const form = useForm({
    name: props.machine.name,
    code: props.machine.code,
    description: props.machine.description || '',
    is_active: props.machine.is_active,
});

const submit = () => form.put(route('machines.update', { tenant: tenant?.id || '', machine: props.machine.id }));
</script>

<template>
    <Head :title="machine.name" />

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
