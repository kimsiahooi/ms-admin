<script setup lang="ts" generic="T">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { computed } from 'vue';
import type { DialogType } from './types';

const props = defineProps<{
    dialog: DialogType<T>;
}>();

const model = defineModel<boolean | undefined>('open');

const emits = defineEmits<{
    submit: [value: undefined];
}>();

const dialogDescription = computed(() => (props.dialog.method === 'delete' ? 'Are you sure you want to delete this data?' : ''));

const submitHandler = () => {
    emits('submit', undefined);
};
</script>

<template>
    <Dialog v-model:open="model">
        <slot name="button" />
        <DialogContent class="sm:max-w-[425px]">
            <form @submit.prevent="submitHandler" class="space-y-4">
                <DialogHeader>
                    <DialogTitle>{{ dialog.title }}</DialogTitle>
                    <DialogDescription>{{ dialogDescription }}</DialogDescription>
                </DialogHeader>
                <div v-if="props.dialog.method !== 'delete'" class="grid gap-4">
                    <slot />
                </div>
                <DialogFooter>
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" class="cursor-pointer"> Close </Button>
                    </DialogClose>
                    <slot name="submit-button" />
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
