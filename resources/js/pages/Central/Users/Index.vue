<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import { useDateTimeFormat } from '@/composables/useDateTimeFormat';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { User } from '@/types/User';
import { Head, Link } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';

defineProps<{
    users: User[];
}>();

const format = useDateTimeFormat();

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
];
</script>

<template>
    <Head title="User List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div v-if="checkPermissions(['Create User'])" class="flex flex-wrap items-center justify-end gap-2">
                <Link :href="route('users.create')" as-child>
                    <Button>Create User</Button>
                </Link>
            </div>

            <Table class="min-w-max">
                <TableCaption>{{ users.length ? 'A list of your users.' : 'No record found.' }}</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-center">ID</TableHead>
                        <TableHead class="text-center">Name</TableHead>
                        <TableHead class="text-center">Email</TableHead>
                        <TableHead class="text-center">Email Verified At</TableHead>
                        <TableHead class="text-center">Created At</TableHead>
                        <TableHead class="text-center">Updated At</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="text-center">{{ user.id }}</TableCell>
                        <TableCell class="text-center">{{ user.name }}</TableCell>
                        <TableCell class="text-center">{{ user.email }}</TableCell>
                        <TableCell class="text-center">{{ format(user.email_verified_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(user.created_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(user.updated_at) }}</TableCell>
                        <TableCell class="text-center">
                            <div class="space-x-2">
                                <Link v-if="checkPermissions(['Delete User'])" :href="route('users.destroy', user.id)" method="delete" as="button">
                                    <Button variant="destructive" size="icon">
                                        <Trash2 />
                                    </Button>
                                </Link>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
