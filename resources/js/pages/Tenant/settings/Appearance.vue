<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/Tenant/AppearanceTabs.vue';
import HeadingSmall from '@/components/Tenant/HeadingSmall.vue';
// biome-ignore lint/style/useImportType: <explanation>
import { AppPageProps, type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import SettingsLayout from '@/layouts/Tenant/settings/Layout.vue';
import { computed } from 'vue';

const page = usePage<AppPageProps>();

const tenant = computed(() => page.props.tenant?.id || '');

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: route('appearance', { tenant: tenant.value }),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Appearance settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
