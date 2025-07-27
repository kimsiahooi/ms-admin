<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

withDefaults(
    defineProps<{
        title: string;
        description?: string;
    }>(),
    {
        description: () => 'Are you sure you want to delete?',
    },
);

const model = defineModel<boolean | undefined>('open');
</script>

<template>
    <Dialog v-model:open="model">
        <DialogTrigger as-child>
            <slot name="trigger">
                <Button class="cursor-pointer" variant="destructive">Delete</Button>
            </slot>
        </DialogTrigger>
        <DialogScrollContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" variant="secondary"> Close </Button>
                </DialogClose>
                <slot>
                    <Button variant="destructive" class="cursor-pointer">Delete</Button>
                </slot>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
