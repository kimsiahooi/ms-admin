<script setup lang="ts" generic="T = null">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import type { DeleteDialogType } from './types';

defineProps<{
    deleteDialog: DeleteDialogType<T>;
}>();

const emits = defineEmits<{
    reset: [];
    delete: [];
}>();

const resetHandler = () => {
    emits('reset');
};

const deleteHandler = () => {
    emits('delete');
};
</script>

<template>
    <Dialog :open="deleteDialog.isOpen">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Delete {{ deleteDialog.title }}</DialogTitle>
                <DialogDescription> Are you sure you want to delete this? </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button class="cursor-pointer" variant="secondary" @click="resetHandler">Cancel</Button>
                <Button class="cursor-pointer" variant="destructive" :disabled="deleteDialog.isDeleting" @click="deleteHandler"> Delete </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
