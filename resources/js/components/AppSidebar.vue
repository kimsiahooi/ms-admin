<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import type { NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Gauge, KeySquare, UserCog, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const { checkPermissions } = useCheckPermissions();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: Gauge,
        isActive: route().current('dashboard'),
    },
    {
        title: 'Roles',
        href: route('roles.index'),
        icon: UserCog,
        isActive: route().current('roles.*'),
        hide: !checkPermissions(['View Role']),
    },
    {
        title: 'Users',
        href: route('users.index'),
        icon: Users,
        isActive: route().current('users.*'),
        hide: !checkPermissions(['View User']),
    },
    {
        title: 'Tenants',
        href: route('tenants.index'),
        icon: KeySquare,
        isActive: route().current('tenants.*'),
        hide: !checkPermissions(['View Tenant']),
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
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
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
