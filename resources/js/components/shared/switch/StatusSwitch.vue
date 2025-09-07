<script setup lang="ts">
import { Tooltip } from '@/components/shared/tooltip';
import { Switch } from '@/components/ui/switch';
import { router, type Method } from '@inertiajs/core';

const props = withDefaults(
    defineProps<{
        value?: boolean | null;
        method: Method;
        href: string;
        tooltip?: string;
    }>(),
    {
        tooltip: 'Toggle Status',
    },
);

const update = () => router.visit(props.href, { method: props.method, data: { status: !props.value }, preserveState: true, preserveScroll: true });
</script>

<template>
    <Tooltip :text="tooltip">
        <Switch v-if="value !== null && value !== undefined" class="cursor-pointer" :model-value="value" @click="update" />
    </Tooltip>
</template>
