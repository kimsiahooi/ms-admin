<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useDateTimeFormat } from '@/composables/useDateTimeFormat';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Tenant } from '@/types/Tenant';
import { Head, Link } from '@inertiajs/vue3';
import { useBrowserLocation } from '@vueuse/core';
import { SquareArrowOutUpRight, Trash2 } from 'lucide-vue-next';

defineProps<{
    tenants: Tenant[];
}>();

const location = useBrowserLocation();

const format = useDateTimeFormat();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Tenants',
        href: route('tenants.index'),
    },
];

const computedLinks = (subdomain: string) => `${location.value.protocol}//${subdomain}.${location.value.host}`;
</script>

<template>
    <Head title="Tenant List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-wrap items-center justify-end gap-2">
                <Link :href="route('tenants.create')" as-child>
                    <Button>Create Tenant</Button>
                </Link>
            </div>

            <Table class="min-w-max">
                <TableCaption>{{ tenants.length ? 'A list of your tenants.' : 'No record found.' }}</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-center">ID</TableHead>
                        <TableHead class="text-center">Name</TableHead>
                        <TableHead class="text-center">Database</TableHead>
                        <TableHead class="text-center">Created At</TableHead>
                        <TableHead class="text-center">Updated At</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="tenant in tenants" :key="tenant.id">
                        <TableCell class="text-center">{{ tenant.id }}</TableCell>
                        <TableCell class="text-center">{{ tenant.name }}</TableCell>
                        <TableCell class="text-center">{{ tenant.tenancy_db_name }}</TableCell>
                        <TableCell class="text-center">{{ format(tenant.created_at) }}</TableCell>
                        <TableCell class="text-center">{{ format(tenant.updated_at) }}</TableCell>
                        <TableCell class="text-center">
                            <div class="space-x-2">
                                <Button as-child size="icon">
                                    <a :href="computedLinks(tenant.id.toString())" target="_blank">
                                        <SquareArrowOutUpRight />
                                    </a>
                                </Button>
                                <Link :href="route('tenants.destroy', tenant.id)" method="delete" as="button">
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
