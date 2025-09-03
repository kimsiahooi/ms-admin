<script setup lang="ts">
import { useError } from '@/composables/useError';
import { computed } from 'vue';

const props = defineProps<{
    errorKey: string;
}>();

const { findErrors } = useError();

const errors = computed(() => findErrors(props.errorKey));
</script>

<template>
    <div v-if="errors">
        <p v-for="error in errors" :key="error.key" class="text-destructive">{{ error.message }}</p>
    </div>
</template>
