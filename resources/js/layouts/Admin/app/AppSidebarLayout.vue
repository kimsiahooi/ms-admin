<script setup lang="ts">
import AppContent from '@/components/Admin/AppContent.vue';
import AppShell from '@/components/Admin/AppShell.vue';
import AppSidebar from '@/components/Admin/AppSidebar.vue';
import AppSidebarHeader from '@/components/Admin/AppSidebarHeader.vue';
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
