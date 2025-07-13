<script setup lang="ts">
import type { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    errorKey: string;
}>();

const page = usePage<AppPageProps>();

const formErrors = computed(() =>
    Object.entries(page.props.errors)
        .filter(([key]) => key.startsWith(props.errorKey))
        .map(([key, error]) => ({ key, message: error })),
);
</script>

<template>
    <div v-if="formErrors.length">
        <p v-for="error in formErrors" :key="error.key" class="text-destructive">{{ error.message }}</p>
    </div>
</template>
