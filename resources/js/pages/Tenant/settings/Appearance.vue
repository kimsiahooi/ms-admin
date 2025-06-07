<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/Tenant/AppearanceTabs.vue';
import HeadingSmall from '@/components/Tenant/HeadingSmall.vue';
// biome-ignore lint/style/useImportType: <explanation>
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: route('admin.appearance'),
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
