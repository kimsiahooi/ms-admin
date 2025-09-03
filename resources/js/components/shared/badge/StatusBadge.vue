<script setup lang="ts">
import { Badge, type BadgeVariants } from '@/components/shared/badge';

defineProps<{
    statusLabel?: {
        name: string | null;
        variant: BadgeVariants['variant'];
    };
}>();
</script>

<template>
    <Badge v-if="statusLabel?.name" :variant="statusLabel.variant">{{ statusLabel.name }}</Badge>
</template>
