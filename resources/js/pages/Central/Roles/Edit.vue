<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import EditPermissions from '@/components/view/roles/EditPermissions.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Permission } from '@/types/Permission';
import type { Role } from '@/types/Role';
import { Head, useForm } from '@inertiajs/vue3';

interface RoleWithPermissions extends Role {
    permissions: Permission[];
}

const props = defineProps<{
    role: RoleWithPermissions;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Roles',
        href: route('roles.index'),
    },
    {
        title: 'Edit',
        href: route('roles.edit', props.role.id),
    },
];

const form = useForm({
    name: props.role.name,
});

const submit = () => form.put(route('roles.update', props.role.id));
</script>

<template>
    <Head title="Edit Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle>Edit {{ role.name }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit">
                        <div class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                <div>
                                    <Label for="name">Name:</Label>
                                    <Input type="text" name="name" v-model="form.name" />
                                    <p v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</p>
                                </div>
                            </div>
                            <div>
                                <Button type="submit" :disabled="form.processing">Update</Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
            <Separator class="my-4" />
            <EditPermissions :role="role" :title="role.name" />
        </div>
    </AppLayout>
</template>
