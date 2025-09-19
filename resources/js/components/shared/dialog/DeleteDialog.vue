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
import type { Method } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';
import { Loader } from 'lucide-vue-next';
import { reactive } from 'vue';

const props = withDefaults(
    defineProps<{
        title: string;
        description?: string;
        route: string;
        method?: Method;
        asChild?: boolean;
        buttonLabel?: string;
    }>(),
    {
        description: 'Are you sure you want to delete?',
        asChild: true,
        method: 'delete',
        buttonLabel: 'Delete',
    },
);

const setting = reactive({
    processing: false,
    open: false,
});

const destroy = () => {
    setting.processing = true;

    router.visit(props.route, {
        method: props.method,
        preserveScroll: true,
        preserveState: true,
        onStart: () => {
            setting.processing = true;
        },
        onSuccess: () => {
            setting.open = false;
        },
        onFinish: () => {
            setting.processing = false;
        },
    });
};
</script>

<template>
    <Dialog v-model:open="setting.open">
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
                    <Button type="button" variant="secondary" class="cursor-pointer"> Close </Button>
                </DialogClose>
                <Button variant="destructive" class="cursor-pointer disabled:cursor-not-allowed" :disabled="setting.processing" @click="destroy">
                    <Loader v-if="setting.processing" class="animate-spin" /> {{ buttonLabel }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
