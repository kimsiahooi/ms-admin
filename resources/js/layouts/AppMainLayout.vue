<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import type { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { toast } from 'vue-sonner';
import 'vue-sonner/style.css';

const page = usePage<AppPageProps>();

const flashMessage = computed(() => page.props.flash);

watch(
    flashMessage,
    (newFlashMessage) => {
        if (newFlashMessage.success) {
            toast.success(newFlashMessage.success);
        }

        if (newFlashMessage.error) {
            toast.error(newFlashMessage.error);
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <slot />
    <Toaster position="top-right" richColors />
</template>
