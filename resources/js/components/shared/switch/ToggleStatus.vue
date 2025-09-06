<script setup lang="ts">
import { Switch } from '@/components/ui/switch';
import { router, type Method } from '@inertiajs/core';

const props = defineProps<{
    value?: boolean | null;
    method: Method;
    href: string;
}>();

const update = () => router.visit(props.href, { method: props.method, data: { status: !props.value } });
</script>

<template>
    <Switch v-if="value !== null || value !== undefined" class="cursor-pointer" :model-value="value" @click="update" />
</template>
