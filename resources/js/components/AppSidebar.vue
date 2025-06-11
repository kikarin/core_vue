<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import * as LucideIcons from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const mainNavItems = ref<NavItem[]>([]);
const settingNavItems = ref<NavItem[]>([]);

// Mapping icon names to components
const iconMap: Record<string, any> = {
    'LayoutGrid': LucideIcons.LayoutGrid,
    'FolderKanban': LucideIcons.FolderKanban,
    'FileStack': LucideIcons.FileStack,
    'Users': LucideIcons.Users,
    'Settings': LucideIcons.Settings,
    'FileText': LucideIcons.FileText,
    'Folder': LucideIcons.Folder,
    'Shield': LucideIcons.Shield,
    'User': LucideIcons.User,
    'List': LucideIcons.List,
    'Plus': LucideIcons.Plus,
    'Edit': LucideIcons.Edit,
    'Trash': LucideIcons.Trash,
    'Search': LucideIcons.Search,
    'Filter': LucideIcons.Filter,
    'Download': LucideIcons.Download,
    'Upload': LucideIcons.Upload,
    'Menu': LucideIcons.Menu,
    'Home': LucideIcons.Home,
    'BarChart': LucideIcons.BarChart,
    'PieChart': LucideIcons.PieChart,
    'Calendar': LucideIcons.Calendar,
    'ShieldCheck': LucideIcons.ShieldCheck,
};

const fetchMenus = async () => {
    try {
        const response = await axios.get('/api/users-menu');
        const menus = response.data;

        // Transform menu data to NavItem format
        const transformMenuToNavItem = (menu: any): NavItem => {
            const navItem: NavItem = {
                title: menu.nama,
                href: menu.url,
                icon: menu.icon ? iconMap[menu.icon] : undefined,
            };

            if (menu.children && menu.children.length > 0) {
                navItem.children = menu.children.map(transformMenuToNavItem);
            }

            return navItem;
        };

        // Split menus into main and settings
        const mainMenus = menus.filter((menu: any) => menu.urutan <= 10);
        const settingMenus = menus.filter((menu: any) => menu.urutan > 10);

    mainNavItems.value = mainMenus.map(transformMenuToNavItem);
        settingNavItems.value = settingMenus.map(transformMenuToNavItem);

        console.log('Main Menus:', mainNavItems.value);
        console.log('Setting Menus:', settingNavItems.value);
    } catch (error) {
        console.error('Error fetching menus:', error);
    }
};

// Refresh menu setiap 5 menit
const refreshInterval = 5 * 60 * 1000; // 5 menit dalam milidetik

onMounted(() => {
    fetchMenus();
    // Set interval untuk refresh menu
    setInterval(fetchMenus, refreshInterval);
});
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
