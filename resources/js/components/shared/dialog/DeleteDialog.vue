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
import { Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        title: string;
        description?: string;
        route: string;
        asChild?: boolean;
    }>(),
    {
        description: 'Are you sure you want to delete?',
        asChild: true,
    },
);

const model = defineModel<boolean>('open');
</script>

<template>
    <Dialog v-model:open="model">
        <DialogTrigger :as-child="asChild">
            <slot>
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
                <Link :href="route" as-child method="delete" :preserve-state="true" :preserve-scroll="true">
                    <Button variant="destructive" class="cursor-pointer">Delete</Button>
                </Link>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
