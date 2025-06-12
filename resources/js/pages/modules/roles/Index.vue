<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  roles: Array<{
    id: number
    name: string
    bg: string
    init_page_login: string
    is_allow_login: string
    is_vertical_menu: string
    created_at: string
    updated_at: string
  }>
}>()

const breadcrumbs = [
    { title: 'Menu & Permissions', href: '#' },
    { title: 'Roles', href: '/menu-permissions/roles' },
]

const columns = [
    { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
    { key: 'bg', label: 'BG', searchable: false, orderable: false, visible: true },
    { key: 'init_page_login', label: 'Default Page', searchable: false, orderable: true, visible: true },
    { key: 'is_allow_login', label: 'Can Login', searchable: false, orderable: false, visible: true },
    { key: 'is_vertical_menu', label: 'Menu Type', searchable: false, orderable: false, visible: true },
]

const actions = (row: any) => [
    {
        label: 'Detail',
        onClick: () => router.visit(`/menu-permissions/roles/${row.id}`)
    },
    {
        label: 'Edit',
        onClick: () => router.visit(`/menu-permissions/roles/${row.id}/edit`)
    },
    {
        label: 'Delete',
        onClick: () => {
            if (confirm(`Delete role "${row.name}"?`)) {
                router.delete(`/menu-permissions/roles/${row.id}`)
            }
        }
    },
    {
        label: 'Set Permissions',
        onClick: () => router.visit(`/menu-permissions/roles/set-permissions/${row.id}`)
    }
]
</script>

<template>
    <PageIndex title="Roles" :breadcrumbs="breadcrumbs" :columns="columns" :rows="props.roles" :actions="actions"
        create-url="/menu-permissions/roles/create" />
</template>
