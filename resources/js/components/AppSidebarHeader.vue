<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Check } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = computed(() => (page.props as any)?.auth?.user);

const allRoles = computed(() => {
    if (!user.value?.users_role) return [];
    return user.value.users_role.map((ur: any) => ur.role).filter(Boolean);
});

const hasMultipleRoles = computed(() => allRoles.value.length > 1);

const handleSwitchRole = (roleId: number) => {
    router.post(
        route('users.switch-role'),
        {
            role_id: roleId,
        },
        {
            preserveState: false, // Force a page reload
        },
    );
};
</script>

<template>
    <header
        class="border-sidebar-border/70 flex h-16 shrink-0 items-center gap-2 border-b px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex flex-1 items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div v-if="user" class="ml-auto">
            <DropdownMenu v-if="hasMultipleRoles">
                <DropdownMenuTrigger>
                    <Badge class="py-2 px-3 text-sm cursor-pointer hover:bg-primary/80">
                        {{ user.role.name }}
                    </Badge>
                </DropdownMenuTrigger>
                <DropdownMenuContent>
                    <DropdownMenuLabel>Switch Role</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem
                        v-for="role in allRoles"
                        :key="role.id"
                        @click="handleSwitchRole(role.id)"
                        class="cursor-pointer"
                    >
                        <Check v-if="role.id === user.role.id" class="mr-2 h-4 w-4" />
                        <span :class="{ 'pl-6': role.id !== user.role.id }">{{ role.name }}</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <Badge v-else class="py-2 px-3 text-sm">{{ user.role.name }}</Badge>
        </div>
    </header>
</template>
