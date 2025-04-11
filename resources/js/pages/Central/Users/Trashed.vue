<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import ForceDeleteUser from '@/components/view/users/ForceDeleteUser.vue';
import RestoreUser from '@/components/view/users/RestoreUser.vue';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import { useDateTimeFormat } from '@/composables/useDateTimeFormat';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Role } from '@/types/Role';
import type { User } from '@/types/User';
import { Head } from '@inertiajs/vue3';

interface UserWithRoles extends User {
    roles: Role[];
}

defineProps<{
    users: UserWithRoles[];
}>();

const format = useDateTimeFormat();

const { checkPermissions } = useCheckPermissions();

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
        title: 'Trashed',
        href: route('users.trashed'),
    },
];
</script>

<template>
    <Head title="Trashed User List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table class="min-w-max">
                <TableCaption>{{ users.length ? 'A list of your users.' : 'No record found.' }}</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-center">ID</TableHead>
                        <TableHead class="text-center">Name</TableHead>
                        <TableHead v-if="checkPermissions(['View Role'])" class="text-center">Roles</TableHead>
                        <TableHead class="text-center">Email</TableHead>
                        <TableHead class="text-center">Email Verified At</TableHead>
                        <TableHead class="text-center">Created At</TableHead>
                        <TableHead class="text-center">Updated At</TableHead>
                        <TableHead class="text-center">Deleted At</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="text-center">{{ user.id }}</TableCell>
                        <TableCell class="text-center">{{ user.name }}</TableCell>
                        <TableCell v-if="checkPermissions(['View Role'])" class="text-center">{{
                            user.roles.map((role) => role.name).join(',')
                        }}</TableCell>
                        <TableCell class="text-center">{{ user.email }}</TableCell>
                        <TableCell class="text-center">{{ format(user.email_verified_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(user.created_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(user.updated_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(user.deleted_at) }}</TableCell>
                        <TableCell class="text-center">
                            <div class="space-x-2">
                                <RestoreUser v-if="checkPermissions(['Restore User'])" :user="user" />
                                <ForceDeleteUser v-if="checkPermissions(['Force Delete User'])" :user="user" />
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
