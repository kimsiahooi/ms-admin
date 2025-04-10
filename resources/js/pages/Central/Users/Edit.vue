<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Role } from '@/types/Role';
import type { User } from '@/types/User';
import { Head, useForm } from '@inertiajs/vue3';

interface UserWithRole extends User {
    roles: Role[];
}

const props = defineProps<{
    user: UserWithRole;
    roles: Role[];
}>();

const checkPermissions = useCheckPermissions();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Users',
        href: route('users.index'),
    },
    {
        title: 'Edit',
        href: route('users.edit', props.user.id),
    },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.roles.map((role) => role.name)[0],
});

const submit = () => form.put(route('users.update', props.user.id));
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle>Edit {{ user.name }}</CardTitle>
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
                                <div>
                                    <Label for="name">Email:</Label>
                                    <Input type="text" name="email" v-model="form.email" />
                                    <p v-if="form.errors.email" class="text-red-500">{{ form.errors.email }}</p>
                                </div>
                                <div v-if="checkPermissions(['Edit Role'])">
                                    <Label for="name">Role:</Label>
                                    <Select v-model="form.role">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select Role" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="form.errors.role" class="text-red-500">{{ form.errors.role }}</p>
                                </div>
                            </div>
                            <div>
                                <Button type="submit" :disabled="form.processing">Update</Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
