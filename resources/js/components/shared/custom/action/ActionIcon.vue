<script setup lang="ts">
import { Button, type ButtonVariants } from '@/components/ui/button';
import type { LucideIcon } from 'lucide-vue-next';

defineProps<{
    icon: LucideIcon;
    variant?: ButtonVariants['variant'];
}>();
</script>

<template>
    <Button size="icon" class="size-6 cursor-pointer rounded-full" :variant="variant">
        <component :is="icon" class="size-3" />
    </Button>
</template>
