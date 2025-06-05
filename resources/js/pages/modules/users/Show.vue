<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Management', href: '#' },
  { title: 'Users', href: '/management/users' },
  { title: 'Detail User', href: '/management/users/show' },
]

const fields = [
  { label: 'Name', value: 'John Doe' },
  { label: 'Email', value: 'john@example.com' },
  { label: 'Role', value: 'Admin' },
  { label: 'Status', value: 'Active' },
]

const actionFields = [
  { label: 'Created At', value: '2025-03-20' },
  { label: 'Created By', value: 'Admin' },
  { label: 'Updated At', value: '2025-04-10' },
  { label: 'Updated By', value: 'Super Admin' },
]

const handleEdit = () => {
  router.visit('/management/users/1/edit')
}

const handleDelete = () => {
  if (confirm('Are you sure you want to delete this user?')) {
    router.delete('/management/users/1')
  }
}
</script>

<template>
  <PageShow
    title="Users"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :actionFields="actionFields"
    :back-url="'/management/users'"
    :on-edit="handleEdit"
    :on-delete="handleDelete"
  />
</template>
