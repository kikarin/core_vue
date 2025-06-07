<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'
import * as LucideIcons from 'lucide-vue-next'


const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Menus', href: '/menu-permissions/menus' },
]

const columns = [
  { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
  { key: 'code', label: 'Code', searchable: true, orderable: true, visible: true },
  { key: 'icon', label: 'Icon', searchable: false, orderable: false, visible: true },
  { key: 'parent', label: 'Parent', searchable: false, orderable: true, visible: true },
  { key: 'permission', label: 'Permission', searchable: true, orderable: true, visible: true },
  { key: 'url', label: 'URL', searchable: true, orderable: true, visible: true },
  { key: 'order', label: 'Order', searchable: false, orderable: true, visible: true },
]

const rows = [
  {
    id: 1,
    name: 'Dashboard',
    code: 'dashboard',
    icon: 'LayoutGrid',
    parent: '-',
    permission: 'view dashboard',
    url: '/dashboard',
    order: 1,
  },
  {
    id: 2,
    name: 'Users',
    code: 'users',
    icon: 'Users',
    parent: 'Management',
    permission: 'manage users',
    url: '/management/users',
    order: 2,
  },
]

const actions = (row: any) => [
  {
    label: 'Detail',
    onClick: () => router.visit(`/menu-permissions/menus/${row.id}`),
  },
  {
    label: 'Edit',
    onClick: () => router.visit(`/menu-permissions/menus/${row.id}/edit`),
  },
  {
    label: 'Delete',
    onClick: () => {
      if (confirm(`Delete menu "${row.name}"?`)) {
        router.delete(`/menu-permissions/menus/${row.id}`)
      }
    },
  },
]
</script>

<template>
  <PageIndex
    title="Menus"
    :breadcrumbs="breadcrumbs"
    :columns="columns"
    :rows="rows"
    :actions="actions"
    create-url="/menu-permissions/menus/create"
  />
</template>
