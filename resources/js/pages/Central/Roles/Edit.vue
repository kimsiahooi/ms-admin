<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Role } from '@/types/Role';
import { Head, useForm } from '@inertiajs/vue3';

const { role } = defineProps<{
    role: Role;
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
        href: route('roles.edit', role.id),
    },
];

const form = useForm({
    name: role.name,
});

const submit = () => form.put(route('roles.update', role.id));
</script>

<template>
    <Head title="Create Tenant" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle>Edit Role</CardTitle>
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
                                <Button type="submit" :disabled="form.processing">Edit</Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
