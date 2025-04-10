<script setup lang="ts">
import Tooltip from '@/components/shared/Tooltip.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import type { User } from '@/types/User';
import { useForm } from '@inertiajs/vue3';
import { ArchiveRestore } from 'lucide-vue-next';

const props = defineProps<{
    user: User;
}>();

const form = useForm({
    id: props.user.id,
});

const submit = () => form.post(route('users.restore', props.user.id));
</script>

<template>
    <Dialog>
        <Tooltip message="Restore User">
            <DialogTrigger as-child>
                <Button variant="secondary" size="icon">
                    <ArchiveRestore />
                </Button>
            </DialogTrigger>
        </Tooltip>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Restore {{ props.user.name }}</DialogTitle>
                <DialogDescription>Are you sure you want to restore this user?</DialogDescription>
                <div class="mt-2 flex flex-wrap items-center justify-end gap-3">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>
                    <form @submit.prevent="submit">
                        <Button type="submit" :disabled="form.processing">Restore</Button>
                    </form>
                </div>
            </DialogHeader>
        </DialogContent>
    </Dialog>
</template>
