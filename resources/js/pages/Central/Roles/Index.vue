<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useDateTimeFormat } from '@/composables/useDateTimeFormat';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Role } from '@/types/Role';
import { Head, Link } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';

defineProps<{
    roles: Role[];
}>();

const format = useDateTimeFormat();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Roles',
        href: route('roles.index'),
    },
];
</script>

<template>
    <Head title="Role List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-wrap items-center justify-end gap-2">
                <Link :href="route('roles.create')" as-child>
                    <Button>Create Role</Button>
                </Link>
            </div>

            <Table>
                <TableCaption>{{ roles.length ? 'A list of your roles.' : 'No record found.' }}</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-center">ID</TableHead>
                        <TableHead class="text-center">Name</TableHead>
                        <TableHead class="text-center">Guard Name</TableHead>
                        <TableHead class="text-center">Created At</TableHead>
                        <TableHead class="text-center">Updated At</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="role in roles" :key="role.id">
                        <TableCell class="text-center">{{ role.id }}</TableCell>
                        <TableCell class="text-center">{{ role.name }}</TableCell>
                        <TableCell class="text-center">{{ role.guard_name }}</TableCell>
                        <TableCell class="text-center">{{ format(role.created_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(role.updated_at) }}</TableCell>
                        <TableCell class="text-center">
                            <div class="space-x-2">
                                <Link :href="route('roles.destroy', role.id)" method="delete" as="button">
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
