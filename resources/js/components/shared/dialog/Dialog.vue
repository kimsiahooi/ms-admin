<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogDescription, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

defineProps<{
    title: string;
    description?: string;
}>();

const model = defineModel<boolean | undefined>('open');
</script>

<template>
    <Dialog v-model:open="model">
        <DialogTrigger as-child>
            <slot name="trigger">
                <Button class="cursor-pointer">Create</Button>
            </slot>
        </DialogTrigger>
        <DialogScrollContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription> </DialogDescription>
            </DialogHeader>
            <div>
                <slot />
            </div>
        </DialogScrollContent>
    </Dialog>
</template>
