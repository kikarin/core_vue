<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'

const props = defineProps<{
  fields: Array<{ label: string, value: string }>,
  actionFields: Array<{ label: string, value: string }>,
  backUrl?: string
}>()

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Activity Logs', href: '/menu-permissions/logs' },
  { title: 'Detail Log', href: '#' },
]

const handleDelete = () => {
  if (confirm('Delete this log entry?')) {
    // Logic for deleting the log entry
    console.log('Log entry deleted')
  }
}
</script>

<template>
  <PageShow
    title="Activity Log"
    :breadcrumbs="breadcrumbs"
    :fields="props.fields"
    :action-fields="props.actionFields"
    :back-url="props.backUrl || '/menu-permissions/logs'"
    :on-delete="handleDelete"
  />
</template>