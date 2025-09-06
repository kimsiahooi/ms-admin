<script setup lang="ts">
import { Badge, type BadgeVariants } from '@/components/shared/badge';

defineProps<{
    statusBadge?: {
        name: string | null;
        variant: BadgeVariants['variant'];
    } | null;
}>();
</script>

<template>
    <Badge v-if="statusBadge?.name" :variant="statusBadge.variant">{{ statusBadge.name }}</Badge>
</template>
