<script setup lang="ts">
import NavFooter from '@/components/Tenant/NavFooter.vue';
import NavMain from '@/components/Tenant/NavMain.vue';
import NavUser from '@/components/Tenant/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import type { AppPageProps, NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage<AppPageProps>();

const tenant = computed(() => page.props.tenant?.id || '');

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant.value }),
        icon: LayoutGrid,
    },
    {
        title: 'Machines',
        href: route('machines.index', { tenant: tenant.value }),
        icon: LayoutGrid,
    },
    {
        title: 'Materials',
        href: route('materials.index', { tenant: tenant.value }),
        icon: LayoutGrid,
    },
    {
        title: 'Products',
        href: route('products.index', { tenant: tenant.value }),
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard', { tenant })">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
