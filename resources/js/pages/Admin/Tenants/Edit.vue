<script setup lang="ts">
import { SwitchOption } from '@/components/shared/switch/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/Admin/AppLayout.vue';
import AppMainLayout from '@/layouts/Admin/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Tenant } from '@/types/Admin/tenants';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    tenant: Tenant;
    options: {
        statuses: SwitchOption<Tenant['status']>[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('admin.dashboard'),
    },
    {
        title: 'Tenants',
        href: route('admin.tenants.index'),
    },
    {
        title: props.tenant.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('admin.tenants.edit', { tenant: props.tenant.id }),
    },
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (config.status ? status.value : !status.value))?.name);

const form = useForm({
    name: props.tenant.name,
    status: props.tenant.status,
});

const config = reactive({
    status: !!form.status,
});

const submit = () => form.put(route('admin.tenants.update', { tenant: props.tenant.id }));

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
    <Head :title="props.tenant.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <form @submit.prevent="submit">
                    <Card class="w-full">
                        <CardHeader>
                            <CardTitle>Edit {{ props.tenant.name }}</CardTitle>
                            <CardDescription></CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid w-full items-center gap-4">
                                <div class="flex flex-col space-y-1.5">
                                    <Label>Name</Label>
                                    <Input type="text" placeholder="Enter Name" v-model:model-value="form.name" />
                                    <p v-if="form.errors.name" class="text-destructive">{{ form.errors.name }}</p>
                                </div>
                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                    <Label class="mb-1">Status</Label>
                                    <div class="flex items-center space-x-2">
                                        <Switch class="cursor-pointer" v-model:model-value="config.status" />
                                        <Label>{{ statusDisplay }}</Label>
                                    </div>
                                    <p v-if="form.errors.status" class="text-destructive">{{ form.errors.status }}</p>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-between px-6">
                            <Button type="submit" class="cursor-pointer" :disabled="form.processing">
                                <Loader v-if="form.processing" class="animate-spin" /> Update
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
