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
import axios from 'axios'
import { onMounted, watch } from 'vue'

const rows = ref<any[]>([])
const total = ref(0)
const loading = ref(false)

const page = ref(1)
const limit = ref(10)
const search = ref('')
const sort = ref({ key: '', order: 'asc' })

const fetchData = async () => {
  loading.value = true
  try {
    const response = await axios.get(props.apiEndpoint, {
      params: {
        search: search.value,
        page: page.value,
        limit: limit.value,
        sort: sort.value.key,
        order: sort.value.order,
      },
    })

    rows.value = response.data.data
    total.value = response.data.total
  } catch (error) {
    console.error('Gagal fetch data:', error)
  } finally {
    loading.value = false
  }
}


onMounted(fetchData)

watch([page, limit, search, () => sort.value], fetchData)


const props = defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  columns: { key: string; label: string }[]
  rows?: any[]
  actions?: (row: any) => { label: string; onClick: () => void }[]
  createUrl: string
  selected?: number[]
  onDeleteSelected?: () => void
  apiEndpoint: string 
}>()

const emit = defineEmits(['search'])

const selected = ref<number[]>(props.selected || [])
const showConfirm = ref(false)

const handleSearch = (params: {
  search?: string
  sortKey?: string
  sortOrder?: 'asc' | 'desc'
  page?: number
  limit?: number
}) => {
  if (params.search !== undefined) search.value = params.search
  if (params.sortKey) sort.value.key = params.sortKey
  if (params.sortOrder) sort.value.order = params.sortOrder
  if (params.page) page.value = params.page
  if (params.limit) limit.value = params.limit
}

const handleDeleteSelected = () => {
  const selectedIds = selected.value.map(index => rows.value[index]?.id).filter(Boolean)
  if (!selectedIds.length) return alert('Pilih data yang akan dihapus')

  // router.delete('/users/destroy-selected', {
  //   data: { id: selectedIds },
  //   onSuccess: () => {
  //     selected.value = []
  //     showConfirm.value = false
  //     router.reload()
  //   },
  //   onError: (errors) => {
  //     console.error(errors)
  //     alert('Gagal menghapus data yang dipilih')
  //   },
  // })
}
</script>

<template>

  <Head :title="title" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <HeaderActions :title="title" :selected="selected" :on-delete-selected="() => (showConfirm = true)"
        v-bind="createUrl ? { createUrl } : {}" />
      <DataTable :columns="columns" :rows="rows" :actions="actions" :total="total" :loading="loading"
        :current-page="page" :page-length="limit" v-model:selected="selected" @search="handleSearch" />


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
