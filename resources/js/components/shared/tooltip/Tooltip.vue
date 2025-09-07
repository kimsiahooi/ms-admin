<script setup lang="ts">
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';

defineProps<{
    text: string | number;
    asChild?: boolean;
}>();
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger :as-child="asChild">
                <slot />
            </TooltipTrigger>
            <TooltipContent>
                <p>{{ text }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
