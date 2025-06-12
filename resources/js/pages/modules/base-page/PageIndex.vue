<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import HeaderActions from './HeaderActions.vue'
import DataTable from '../components/DataTable.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { type BreadcrumbItem } from '@/types'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'

const props = defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  columns: { key: string; label: string }[]
  rows: any[]
  actions?: (row: any) => { label: string; onClick: () => void }[]
  createUrl: string
  selected?: number[]
  onDeleteSelected?: () => void
}>()

const emit = defineEmits(['search'])

const selected = ref<number[]>(props.selected || [])
const showConfirm = ref(false)

const handleSearch = (params: any) => emit('search', params)

const handleDeleteSelected = () => {
  const selectedIds = selected.value.map(index => props.rows[index]?.id).filter(Boolean)
  if (!selectedIds.length) return alert('Pilih data yang akan dihapus')

  router.delete('/users/destroy-selected', {
    data: { id: selectedIds },
    onSuccess: () => {
      selected.value = []
      showConfirm.value = false
      router.reload()
    },
    onError: (errors) => {
      console.error(errors)
      alert('Gagal menghapus data yang dipilih')
    },
  })
}
</script>

<template>
  <Head :title="title" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <HeaderActions
        :title="title"
        :selected="selected"
        :on-delete-selected="() => (showConfirm = true)"
        v-bind="createUrl ? { createUrl } : {}"
      />

      <DataTable
        :columns="columns"
        :rows="rows"
        :actions="actions"
        v-model:selected="selected"
        @search="handleSearch"
      />

      <Dialog v-model:open="showConfirm">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Hapus data terpilih?</DialogTitle>
            <DialogDescription>
              Anda akan menghapus {{ selected.length }} data. Tindakan ini tidak dapat dibatalkan.
            </DialogDescription>
          </DialogHeader>
          <DialogFooter>
            <Button variant="outline" @click="showConfirm = false">Batal</Button>
            <Button variant="destructive" @click="handleDeleteSelected">Hapus</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>
