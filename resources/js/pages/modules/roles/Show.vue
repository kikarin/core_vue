<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Roles', href: '/menu-permissions/roles' },
  { title: 'Detail Role', href: '/menu-permissions/roles/show' },
]

const fields = [
  { label: 'Name', value: 'Admin' },
  { label: 'Default Page', value: '/dashboard' },
  { label: 'Can Login', value: 'Ya' },
]

const actionFields = [
  { label: 'Created At', value: '2025-03-20' },
  { label: 'Created By', value: 'System' },
  { label: 'Updated At', value: '2025-04-10' },
  { label: 'Updated By', value: 'Admin' },
]

const handleEdit = () => {
  router.visit('/menu-permissions/roles/1/edit')
}
const handleDelete = () => {
  if (confirm('Are you sure you want to delete this role?')) {
    router.delete('/menu-permissions/roles/1')
  }
}
</script>

<template>
  <PageShow
    title="Roles"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :action-fields="actionFields"
    :back-url="'/menu-permissions/roles'"
    :on-edit="handleEdit"
    :on-delete="handleDelete"
  />
</template>
