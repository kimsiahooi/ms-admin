<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import type { NavItem, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage<SharedData>();

const authPermissions = computed(() => page.props.auth.permissions);

const checkPermissions = useCheckPermissions();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
        isActive: route().current('dashboard'),
    },
    {
        title: 'Roles',
        href: route('roles.index'),
        icon: LayoutGrid,
        isActive: route().current('roles.*'),
        hide: !checkPermissions(
            authPermissions.value?.map((permission) => permission.name),
            ['View Role'],
        ),
    },
    {
        title: 'Users',
        href: route('users.index'),
        icon: LayoutGrid,
        isActive: route().current('users.*'),
    },
    {
        title: 'Tenants',
        href: route('tenants.index'),
        icon: LayoutGrid,
        isActive: route().current('tenants.*'),
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
