<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Permissions', href: '/menu-permissions/permissions' },
  { title: 'Detail Permission', href: '#' },
]

const fields = [
  { label: 'Name', value: 'Users Create' },
]

const actionFields = [
  { label: 'Created At', value: '2025-03-20' },
  { label: 'Created By', value: 'System' },
  { label: 'Updated At', value: '2025-04-10' },
  { label: 'Updated By', value: 'Admin' },
]

const handleEdit = () => {
  router.visit('/menu-permissions/permissions/1/edit-permission')
}

const handleDelete = () => {
  if (confirm('Are you sure you want to delete this permission?')) {
    router.delete('/menu-permissions/permissions/delete-permission/1')
  }
}
</script>

<template>
  <PageShow
    title="Permission"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :action-fields="actionFields"
    :back-url="'/menu-permissions/permissions'"
    :on-edit="handleEdit"
    :on-delete="handleDelete"
  />
</template> 