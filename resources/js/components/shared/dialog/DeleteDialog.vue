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
import { router } from '@inertiajs/vue3';

const props = withDefaults(
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

const model = defineModel<boolean | undefined>('open');

const deleteHandler = () =>
    router.delete(props.route, {
        onSuccess: () => {
            model.value = false;
        },
    });
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
                <Button variant="destructive" class="cursor-pointer" @click="deleteHandler">Delete</Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
