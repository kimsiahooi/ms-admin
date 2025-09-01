<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Loader } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        label?: string;
        disabled?: boolean;
        loading?: boolean;
        type?: 'button' | 'submit';
    }>(),
    {
        label: 'Create',
    },
);
</script>

<template>
    <div class="flex flex-wrap items-center gap-2">
        <Button :type="type" class="cursor-pointer disabled:cursor-not-allowed" :disabled="disabled">
            <Loader v-if="loading" class="animate-spin" /> {{ props.label }}
        </Button>
        <slot />
    </div>
</template>
