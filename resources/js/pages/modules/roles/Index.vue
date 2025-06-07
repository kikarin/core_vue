<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
    { title: 'Menu & Permissions', href: '#' },
    { title: 'Roles', href: '/menu-permissions/roles' },
]

const columns = [
    { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
    { key: 'default_page', label: 'Default Page', searchable: false, orderable: true, visible: true },
    { key: 'can_login', label: 'Can Login', searchable: false, orderable: false, visible: true },
]

const rows = [
    { id: 1, name: 'Admin', default_page: '/dashboard', can_login: 'Ya' },
    { id: 2, name: 'Guest', default_page: '-', can_login: 'Tidak' },
]

const actions = (row: any) => [
    {
        label: 'Detail',
        onClick: () => {
            router.visit(`/menu-permissions/roles/${row.id}`)
        },
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
    <PageIndex title="Roles" :breadcrumbs="breadcrumbs" :columns="columns" :rows="rows" :actions="actions"
        create-url="/menu-permissions/roles/create" />
</template>
