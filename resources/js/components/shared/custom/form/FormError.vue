<script setup lang="ts">
defineProps<{
    error?: string;
}>();
</script>

<template>
    <p v-if="error" class="text-destructive">{{ error }}</p>
</template>
