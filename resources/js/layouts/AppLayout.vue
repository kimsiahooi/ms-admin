<script setup lang="ts">
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const { toast } = useToast();

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
            toast({
                title: 'Something went wrong!',
                description: newFlashMessage.error,
                variant: 'destructive',
            });
        } else if (newFlashMessage.success) {
            toast({
                title: 'Succeed!',
                description: newFlashMessage.success,
                variant: 'success',
            });
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster />
    </AppLayout>
</template>
