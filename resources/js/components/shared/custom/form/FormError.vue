<script setup lang="ts">
import { ErrorMessages } from '@/components/shared/error';

defineProps<{
    error?: string;
    errorKey?: string;
}>();
</script>

<template>
    <p v-if="error" class="text-destructive">{{ error }}</p>
    <ErrorMessages v-if="errorKey" :error-key="errorKey" />
</template>
