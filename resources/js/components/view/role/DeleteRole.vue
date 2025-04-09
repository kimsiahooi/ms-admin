<script setup lang="ts">
import Tooltip from '@/components/shared/Tooltip.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import type { Role } from '@/types/Role';
import { useForm } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    role: Role;
}>();

const form = useForm({
    id: props.role.id,
});

const submit = () => form.delete(route('roles.destroy', props.role.id));
</script>

<template>
    <Dialog>
        <Tooltip message="Delete Role">
            <DialogTrigger as-child>
                <Button variant="destructive" size="icon">
                    <Trash2 />
                </Button>
            </DialogTrigger>
        </Tooltip>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Delete Role {{ props.role.name }}</DialogTitle>
                <DialogDescription>Are you sure you want to delete role?</DialogDescription>
                <div class="mt-2 flex flex-wrap items-center justify-end gap-3">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>
                    <form @submit.prevent="submit">
                        <Button variant="destructive" :disabled="form.processing">Delete</Button>
                    </form>
                </div>
            </DialogHeader>
        </DialogContent>
    </Dialog>
</template>
