<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, FolderKanban, FileStack, Users, ShieldCheck } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Management',
        href: '/management',
        icon: FolderKanban,
        children: [
            {
                title: 'Teams',
                href: '/management/teams',
                children: [
                    {
                        title: 'List Team',
                        href: '/management/teams',
                    },
                    {
                        title: 'Member',
                        href: '/management/members',
                    },
                ],
            },
        ],
    },
];

const settingNavItems: NavItem[] = [
    {
        title: 'Data Master',
        icon: FileStack,
        href: '/data-master',
        children: [
            {
                title: 'Nama Team',
                href: '/data-master/team-names',
            },
            {
                title: 'Item 2',
                href: '/data-master/item-2',
            },
            {
                title: 'Item 3',
                href: '/data-master/item-3',
            },
        ],
    },
    {
        title: 'Users',
        href: '/users',
        icon: Users,
    },
    {
        title: 'Menu & Permissions',
        icon: ShieldCheck,
        href: '/menu-permissions',
        children: [
            {
                title: 'Menu',
                href: '/menu-permissions/menus',
            },
            {
                title: 'Role',
                href: '/menu-permissions/roles',
            },
            {
                title: 'Permission',
                href: '/menu-permissions/permissions',
            },
        ],
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
            <NavMain :items="mainNavItems" section-title="Menu" section-id="main" />
            <NavMain :items="settingNavItems" section-title="Settings" section-id="setting" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>
