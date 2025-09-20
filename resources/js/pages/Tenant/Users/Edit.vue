<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput } from '@/components/shared/custom/form';
import { useTenant } from '@/composables/useTenant';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { TenantUser } from '@/types/Tenant/tenant-users';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    user: TenantUser;
}>();

const { tenant } = useTenant();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Users',
        href: route('users.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.user.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('users.edit', { tenant: tenant?.id || '', user: props.user.id }),
    },
]);

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});

const submit = () =>
    form.put(route('users.update', { tenant: tenant?.id || '', user: props.user.id }), {
        preserveScroll: true,
        preserveState: true,
    });
</script>

<template>
    <Head :title="user.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${user.name}`">
                    <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                    <FormInput label="Email" :error="form.errors.email" v-model:model-value="form.email" type="email" />
                    <FormInput label="Password" :error="form.errors.password" v-model:model-value="form.password" type="password" />
                    <FormInput
                        label="Confirm Password"
                        :error="form.errors.password_confirmation"
                        v-model:model-value="form.password_confirmation"
                        type="password"
                    />
                    <FormButton type="submit" :disabled="form.processing" label="Update" :loading="form.processing" />
                </Card>
            </form>
        </Layout>
    </AppLayout>
</template>
