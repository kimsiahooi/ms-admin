<script setup lang="ts">
import { badgeVariants, type BadgeVariants } from '@/components/shared/badge';
import { cn } from '@/lib/utils';
import { reactiveOmit } from '@vueuse/core';
import type { PrimitiveProps } from 'reka-ui';
import { Primitive } from 'reka-ui';
import type { HTMLAttributes } from 'vue';

const props = defineProps<
    PrimitiveProps & {
        variant?: BadgeVariants['variant'];
        class?: HTMLAttributes['class'];
    }
>();

const delegatedProps = reactiveOmit(props, 'class');
</script>

<template>
    <Primitive data-slot="badge" :class="cn(badgeVariants({ variant }), props.class)" v-bind="delegatedProps">
        <slot />
    </Primitive>
</template>
