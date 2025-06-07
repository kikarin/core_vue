<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Menus', href: '/menu-permissions/menus' },
  { title: 'Detail Menu', href: '/menu-permissions/menus/show' },
]

const fields = [
  { label: 'Name', value: 'Dashboard' },
  { label: 'Code', value: 'dashboard' },
  { label: 'Icon', value: 'LayoutGrid' },
  { label: 'Parent', value: '-' },
  { label: 'Permission', value: 'view dashboard' },
  { label: 'URL', value: '/dashboard' },
  { label: 'Order', value: '1' },
]

const actionFields = [
  { label: 'Created At', value: '2025-03-01' },
  { label: 'Created By', value: 'Admin' },
  { label: 'Updated At', value: '2025-04-01' },
  { label: 'Updated By', value: 'Super Admin' },
]

const handleEdit = () => router.visit('/menu-permissions/menus/1/edit')
const handleDelete = () => {
  if (confirm('Delete this menu?')) {
    router.delete('/menu-permissions/menus/1')
  }
}
</script>

<template>
  <PageShow
    title="Menus"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :action-fields="actionFields"
    :back-url="'/menu-permissions/menus'"
    :on-edit="handleEdit"
    :on-delete="handleDelete"
  />
</template>
