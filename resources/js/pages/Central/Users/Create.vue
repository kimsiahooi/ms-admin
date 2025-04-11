<script setup lang="ts">
import TagInput from '@/components/shared/TagInput.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Role, UserRole } from '@/types/Role';
import { Head, useForm } from '@inertiajs/vue3';

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
        title: 'Create',
        href: route('users.create'),
    },
];

defineProps<{
    roles: Role[];
}>();

const { checkPermissions } = useCheckPermissions();

const pushRole = (role: UserRole) => form.roles.push(role);

const removeRole = (role: UserRole) => {
    form.roles = form.roles.filter((r) => r !== role);
};

const form = useForm<{
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    roles: UserRole[];
}>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
});

const submit = () =>
    form.post(route('users.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
</script>

<template>
    <Head title="Create User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle>Create User</CardTitle>
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
                                <div>
                                    <Label for="name">Password:</Label>
                                    <Input type="password" name="email" v-model="form.password" />
                                    <p v-if="form.errors.password" class="text-red-500">{{ form.errors.password }}</p>
                                </div>
                                <div>
                                    <Label for="name">Confirm Password:</Label>
                                    <Input type="password" name="email" v-model="form.password_confirmation" />
                                    <p v-if="form.errors.password_confirmation" class="text-red-500">{{ form.errors.password_confirmation }}</p>
                                </div>
                                <div v-if="checkPermissions(['Edit Role'])">
                                    <Label for="name">Roles:</Label>
                                    <TagInput
                                        :values="roles.map((role) => role.name)"
                                        :model-value="form.roles"
                                        placeholder="Search Roles"
                                        @push-value="pushRole"
                                        @remove-value="removeRole"
                                    />
                                    <p v-if="form.errors.roles" class="text-red-500">{{ form.errors.roles }}</p>
                                </div>
                            </div>
                            <div>
                                <Button type="submit" :disabled="form.processing">Create</Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
