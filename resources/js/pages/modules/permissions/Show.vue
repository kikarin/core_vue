<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Permissions', href: '/menu-permissions/permissions' },
  { title: 'Detail Category', href: '#' },
]

const fields = [
  { label: 'Name', value: 'Users' },
  { label: 'Sequence', value: '1' },
]

const actionFields = [
  { label: 'Created At', value: '2025-05-01' },
  { label: 'Created By', value: 'Super Admin' },
  { label: 'Updated At', value: '2025-06-05' },
  { label: 'Updated By', value: 'Admin' },
]
</script>

<template>
  <PageShow
    title="Permission Category"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :action-fields="actionFields"
    back-url="/menu-permissions/permissions"
  />
</template>
