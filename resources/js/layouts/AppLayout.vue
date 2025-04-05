<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { Toaster, toast } from 'vue-sonner';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage<SharedData>();

const flashMessage = computed(() => page.props.flash);

watch(
    flashMessage,
    (newFlashMessage) => {
        if (newFlashMessage.error) {
            toast.error(newFlashMessage.error);
        } else if (newFlashMessage.success) {
            toast.success(newFlashMessage.success);
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster closeButton richColors />
    </AppLayout>
</template>
