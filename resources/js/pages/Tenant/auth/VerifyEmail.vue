<script setup lang="ts">
import TextLink from '@/components/Tenant/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/Tenant/AuthLayout.vue';
import type { AppPageProps } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps<{
    status?: string;
}>();

const page = usePage<AppPageProps>();

const tenant = computed(() => page.props.tenant?.id || '');

const form = useForm({});

const submit = () => {
    form.post(route('verification.send', { tenant: tenant.value }));
};
</script>

<template>
    <AuthLayout title="Verify email" description="Please verify your email address by clicking on the link we just emailed to you.">
        <Head title="Email verification" />

        <div v-if="status === 'verification-link-sent'" class="mb-4 text-center text-sm font-medium text-green-600">
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit" class="space-y-6 text-center">
            <Button :disabled="form.processing" variant="secondary">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                Resend verification email
            </Button>

            <TextLink :href="route('logout', { tenant })" method="post" as="button" class="mx-auto block text-sm"> Log out </TextLink>
        </form>
    </AuthLayout>
</template>
