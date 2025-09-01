<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogDescription, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

const props = withDefaults(
    defineProps<{
        title: string;
        description?: string;
        triggerLabel?: string;
    }>(),
    {
        triggerLabel: 'Create',
    },
);

const model = defineModel<boolean>('open');
</script>

<template>
    <Dialog v-model:open="model">
        <DialogTrigger as-child>
            <slot name="trigger">
                <Button class="cursor-pointer">{{ props.triggerLabel }}</Button>
            </slot>
        </DialogTrigger>
        <DialogScrollContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </DialogHeader>
            <div>
                <slot />
            </div>
        </DialogScrollContent>
    </Dialog>
</template>
