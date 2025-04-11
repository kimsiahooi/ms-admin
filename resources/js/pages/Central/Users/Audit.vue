<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useDateTimeFormat } from '@/composables/useDateTimeFormat';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Audit } from '@/types/Audit';
import type { User } from '@/types/User';
import { Head } from '@inertiajs/vue3';

defineProps<{
    audits: Audit<User>[];
}>();

const format = useDateTimeFormat();

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
        title: 'Audits',
        href: route('users.audits'),
    },
];
</script>

<template>
    <Head title="User Audit List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table class="min-w-max">
                <TableCaption>{{ audits.length ? 'A list of your user audits.' : 'No record found.' }}</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-center">ID</TableHead>
                        <TableHead class="text-center">Log Name</TableHead>
                        <TableHead class="text-center">Description</TableHead>
                        <TableHead class="text-center">Event</TableHead>
                        <TableHead class="text-center">Subject ID</TableHead>
                        <TableHead class="text-center">Causer ID</TableHead>
                        <TableHead class="text-center">Properties</TableHead>
                        <TableHead class="text-center">Created At</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="audit in audits" :key="audit.id">
                        <TableCell class="text-center">{{ audit.id }}</TableCell>
                        <TableCell class="text-center">{{ audit.log_name }}</TableCell>
                        <TableCell class="text-center">{{ audit.description }}</TableCell>
                        <TableCell class="text-center">{{ audit.event }}</TableCell>
                        <TableCell class="text-center">{{ audit.subject_id }}</TableCell>
                        <TableCell class="text-center">{{ audit.causer_id }}</TableCell>
                        <TableCell>
                            <div class="space-y-3">
                                <div v-if="audit.properties.attributes" class="space-y-1">
                                    <p class="font-bold">New Value:</p>
                                    <div class="rounded-lg bg-primary p-3 text-primary-foreground">
                                        <pre>
                                            <code>
                                                {{ JSON.stringify(audit.properties.attributes, null, 2) }}
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                                <div v-if="audit.properties.old" class="space-y-1">
                                    <p class="font-bold">Old Value:</p>
                                    <div class="rounded-lg bg-primary p-3 text-primary-foreground">
                                        <pre>
                                            <code>
                                                {{ JSON.stringify(audit.properties.old, null, 2) }}
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </TableCell>
                        <TableCell class="text-center">{{ format(audit.created_at) }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
