<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import HeaderActions from './HeaderActions.vue'
import DataTable from '../components/DataTable.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { type BreadcrumbItem } from '@/types'

const selected = ref<number[]>([])
const showConfirm = ref<boolean>(false)

const deleteSelected = () => {
  alert(`Deleted: ${selected.value.join(', ')}`)
  selected.value = []
}


defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  columns: { key: string; label: string }[]
  rows: any[]
  actions?: (row: any) => { label: string; onClick: () => void }[]
  createUrl: string
}>()

</script>

<template>

  <Head :title="title" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <HeaderActions :title="title" :create-url="createUrl || '#'" :selected="selected"
        :on-delete-selected="() => (showConfirm = true)" />
      <DataTable :columns="columns" :rows="rows" :actions="actions" v-model:selected="selected" />
      <ConfirmDialog v-model="showConfirm" title="Delete selected?"
        :description="`You are about to delete ${selected.length} record(s). This action cannot be undone.`"
        @confirm="deleteSelected" />
    </div>
  </AppLayout>
</template>
