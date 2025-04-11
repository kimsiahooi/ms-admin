<script setup lang="ts">
import Tooltip from '@/components/shared/Tooltip.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import DeleteUser from '@/components/view/users/DeleteUser.vue';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import { useDateTimeFormat } from '@/composables/useDateTimeFormat';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Role } from '@/types/Role';
import type { User } from '@/types/User';
import { Head, Link } from '@inertiajs/vue3';
import { Pencil } from 'lucide-vue-next';

interface UserWithRoles extends User {
    roles: Role[];
}

defineProps<{
    users: UserWithRoles[];
}>();

const format = useDateTimeFormat();

const { checkPermissions, checkAnyPermissions } = useCheckPermissions();

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
            <div class="flex flex-wrap items-center justify-end gap-3">
                <Link v-if="checkPermissions(['Create User'])" :href="route('users.create')" as-child>
                    <Button>Create User</Button>
                </Link>

                <Link v-if="checkPermissions(['View Trashed User'])" :href="route('users.trashed')" as-child>
                    <Button>View Trashed</Button>
                </Link>
            </div>

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
                        <TableHead v-if="checkAnyPermissions(['Edit User', 'Delete User'])" class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="text-center">{{ user.id }}</TableCell>
                        <TableCell class="text-center">{{ user.name }}</TableCell>
                        <TableCell v-if="checkPermissions(['View Role'])" class="text-center">{{
                            user.roles.map((role) => role.name).join(', ')
                        }}</TableCell>
                        <TableCell class="text-center">{{ user.email }}</TableCell>
                        <TableCell class="text-center">{{ format(user.email_verified_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(user.created_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(user.updated_at) }}</TableCell>
                        <TableCell v-if="checkAnyPermissions(['Edit User', 'Delete User'])" class="text-center">
                            <div class="space-x-2">
                                <Tooltip v-if="checkPermissions(['Edit User'])" message="Edit User">
                                    <Link :href="route('users.edit', user.id)">
                                        <Button size="icon" variant="secondary">
                                            <Pencil />
                                        </Button>
                                    </Link>
                                </Tooltip>
                                <DeleteUser v-if="checkPermissions(['Delete User'])" :user="user" />
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
