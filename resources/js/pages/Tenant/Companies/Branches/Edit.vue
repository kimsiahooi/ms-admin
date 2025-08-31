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
import type { Company, CompanyBranch } from '@/types/Tenant/companies';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    company: Company;
    branch: CompanyBranch;
    options: {
        statuses: SwitchOption<Company['status']>[];
    };
}>();

const { tenant } = useTenant();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Companies',
        href: route('companies.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.company.name,
        href: '#',
    },
    {
        title: 'Branches',
        href: route('companies.branches.index', { tenant: tenant?.id || '', company: props.company.id }),
    },
    {
        title: 'Edit',
        href: route('companies.branches.edit', { tenant: tenant?.id || '', company: props.company.id, branch: props.branch.id }),
    },
];

const statusDisplay = computed(() => props.options.statuses.find((status) => (form.status ? status.value : !status.value))?.name);

const form = useForm({
    name: props.branch.name,
    code: props.branch.code,
    description: props.branch.description || '',
    address: props.branch.address,
    status: props.branch.status,
});

const config = reactive({
    status: !!form.status,
});

const submit = () => form.put(route('companies.branches.update', { tenant: tenant?.id || '', company: props.company.id, branch: props.branch.id }));

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
    <Head :title="branch.name" />

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
                        <Label>Address</Label>
                        <Textarea placeholder="Enter Address" v-model:model-value="form.address" />
                        <p v-if="form.errors.address" class="text-destructive">{{ form.errors.address }}</p>
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
