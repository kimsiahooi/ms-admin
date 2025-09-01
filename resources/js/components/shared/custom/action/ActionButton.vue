<script setup lang="ts">
import { ActionIcon } from '@/components/shared/custom/action';
import { Tooltip } from '@/components/shared/tooltip';
import type { ButtonVariants } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

defineProps<{
    text: string;
    href?: string;
    icon: LucideIcon;
    variant?: ButtonVariants['variant'];
}>();
</script>

<template>
    <Tooltip :text="text">
        <Link v-if="href" :href="href" as-child>
            <ActionIcon :icon="icon" :variant="variant" />
        </Link>
        <ActionIcon v-else :icon="icon" :variant="variant" />
    </Tooltip>
</template>
