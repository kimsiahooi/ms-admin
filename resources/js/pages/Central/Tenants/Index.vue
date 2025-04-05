<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

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
</script>

<template>
    <Head title="Tenant List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <Link :href="route('tenants.create')" as-child>
                    <Button>Create Tenant</Button>
                </Link>
            </div>
            <Card>
                <CardHeader>
                    <CardTitle>Tenants</CardTitle>
                    <CardDescription>Will add filters in future</CardDescription>
                </CardHeader>
                <CardContent></CardContent>
            </Card>
            <Separator class="my-4" />
            <Table>
                <TableCaption>A list of your tenants.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-center">ID</TableHead>
                        <TableHead class="text-center">Name</TableHead>
                        <TableHead class="text-center">Database</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow>
                        <TableCell class="text-center"></TableCell>
                        <TableCell class="text-center"></TableCell>
                        <TableCell class="text-center"></TableCell>
                        <TableCell class="text-center"></TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
