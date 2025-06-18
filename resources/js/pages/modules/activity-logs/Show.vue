<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'
import { router } from '@inertiajs/vue3'
import { useToast } from '@/components/ui/toast/useToast'

const { toast } = useToast()

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
  // Extract log ID from the URL or props if available
  const logId = window.location.pathname.split('/').pop()
  if (logId) {
    router.delete(`/menu-permissions/logs/${logId}`, {
      onSuccess: () => {
        toast({ title: 'Log berhasil dihapus', variant: 'success' })
        router.visit('/menu-permissions/logs')
      },
      onError: () => {
        toast({ title: 'Gagal menghapus log', variant: 'destructive' })
      }
    })
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