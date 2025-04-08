<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import type { Method } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    title: string;
    description?: string;
    href: string;
    method: Method;
}>();

const dialogIsOpen = ref(false);

const submit = () =>
    router.visit(props.href, {
        method: props.method,
        onSuccess: () => {
            dialogIsOpen.value = false;
        },
    });
</script>

<template>
    <Dialog :default-open="dialogIsOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>
                    {{ description }}
                </DialogDescription>
                <div>
                    <p class="mb-5">Are you sure you want to delete?</p>
                    <div class="flex items-center justify-end gap-3">
                        <DialogClose as-child>
                            <Button variant="secondary">Close</Button>
                        </DialogClose>
                        <form @submit.prevent="submit">
                            <Button variant="destructive" type="submit">Delete</Button>
                        </form>
                    </div>
                </div>
            </DialogHeader>
        </DialogContent>
    </Dialog>
</template>
