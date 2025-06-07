<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Activity Logs', href: '/menu-permissions/logs' },
  { title: 'Detail Log', href: '/menu-permissions/logs/1' },
]

const fields = [
  { label: 'Module', value: 'Users' },
  { label: 'Event', value: 'Updated' },
  { label: 'Subject Type', value: 'App\\Models\\User' },
  { label: 'Subject ID', value: '5' },
  { label: 'Data', value: 'Changed name from "John" to "Johnny"' },
]

const actionFields = [
  { label: 'Created At', value: '2025-06-05 10:00' },
  { label: 'Created By', value: 'Admin' },
]
</script>

<template>
  <PageShow
    title="Activity Log"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :action-fields="actionFields"
    :back-url="'/menu-permissions/logs'"
  />
</template>
