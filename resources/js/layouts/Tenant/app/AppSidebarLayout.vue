<script setup lang="ts">
import AppContent from '@/components/Tenant/AppContent.vue';
import AppShell from '@/components/Tenant/AppShell.vue';
import AppSidebar from '@/components/Tenant/AppSidebar.vue';
import AppSidebarHeader from '@/components/Tenant/AppSidebarHeader.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="max-w-full overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>
