<script setup lang="ts">
import { Switch } from '@/components/ui/switch';
import { router, type Method } from '@inertiajs/core';

const props = defineProps<{
    value?: boolean;
    method: Method;
    href: string;
}>();

const update = () => router.visit(props.href, { method: props.method });
</script>

<template>
    <Switch class="cursor-pointer" :model-value="value" @click="update" />
</template>
