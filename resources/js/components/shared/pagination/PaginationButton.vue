<script setup lang="ts">
import type { PaginateLink } from '@/components/shared/pagination/types';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    value: number;
    isActive: boolean;
    links: PaginateLink[];
}>();

const link = computed(() => props.links.find((link) => link.label === props.value.toString())?.url);
</script>

<template>
    <Link v-if="link" :href="link" as-child>
        <Button size="icon" class="cursor-pointer" :variant="isActive ? 'default' : 'outline'">
            {{ value }}
        </Button>
    </Link>
    <Button v-else size="icon" class="cursor-pointer" disabled :variant="isActive ? 'default' : 'outline'">{{ value }}</Button>
</template>
