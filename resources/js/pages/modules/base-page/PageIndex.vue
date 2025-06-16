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
import debounce from 'lodash.debounce'
import { useToast } from '@/components/ui/toast/useToast'

const { toast } = useToast()

const rows = ref<any[]>([])
const total = ref(0)
const loading = ref(false)

const page = ref(1)
const limit = ref(10)
const search = ref('')
const sort = ref<{ key: string; order: 'asc' | 'desc' }>({ key: '', order: 'asc' })

const fetchData = async () => {
  loading.value = true
  try {
    const response = await axios.get(props.apiEndpoint, {
      params: {
        search: search.value,
        page: page.value > 1 ? page.value - 1 : 0,
        per_page: limit.value,
        sort: sort.value.key,
        order: sort.value.order,
      },
    })

    rows.value = response.data.data
    const meta = response.data.meta || {}
    total.value = Number(meta.total) || 0
    page.value = Number(meta.current_page) || 1
    limit.value = Number(meta.per_page) || 10
    search.value = meta.search || ''
    sort.value.key = meta.sort || ''
    sort.value.order = meta.order || 'asc'
  } catch (error) {
    console.error('Gagal fetch data:', error)
  } finally {
    loading.value = false
  }
}

const debouncedFetchData = debounce(fetchData, 400)

onMounted(fetchData)

watch(search, () => {
  debouncedFetchData()
})

watch([page, limit, () => sort.value.key, () => sort.value.order], fetchData)

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

const emit = defineEmits(['search', 'update:selected'])

const selected = ref<number[]>([])

watch(() => props.selected, (val) => {
  if (val) selected.value = val
})

watch(selected, (val) => {
  emit('update:selected', val)
})

const showConfirm = ref(false)
const showDeleteDialog = ref(false)
const rowToDelete = ref<any>(null)

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
  if (!selected.value.length) return
  if (props.onDeleteSelected) {
    props.onDeleteSelected()
  }
  showConfirm.value = false
}


const handleDeleteRow = (row: any) => {
  rowToDelete.value = row
  showDeleteDialog.value = true
}

const confirmDeleteRow = () => {
  if (!rowToDelete.value) return
  router.delete(`/users/${rowToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteDialog.value = false
      rowToDelete.value = null
      fetchData()
      toast({ title: 'Data berhasil dihapus', variant: 'success' }) 
    },
    onError: () => {
      showDeleteDialog.value = false
      rowToDelete.value = null
      toast({ title: 'Gagal menghapus data.', variant: 'destructive' }) 
    }
  })
}


const localActions = (row: any) => {
  const base = props.actions ? props.actions(row) : []
  return base.map(action => {
    if (action.label === 'Delete') {
      return {
        ...action,
        onClick: () => handleDeleteRow(row)
      }
    }
    return action
  })
}

defineExpose({ fetchData })
</script>

<template>

  <Head :title="title" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <HeaderActions :title="title" :selected="selected" :on-delete-selected="() => (showConfirm = true)"
        v-bind="createUrl ? { createUrl } : {}" />
      <DataTable :columns="columns" :rows="rows" :actions="localActions" :total="total" :loading="loading"
        v-model:selected="selected" :search="search" :sort="sort" :page="page" :per-page="limit"
        @update:search="(val) => handleSearch({ search: val })"
        @update:sort="(val) => handleSearch({ sortKey: val.key, sortOrder: val.order })"
        @update:page="(val) => handleSearch({ page: val })"
        @update:perPage="(val) => handleSearch({ limit: Number(val), page: 1 })" @deleted="fetchData()"
        :on-delete-row="handleDeleteRow" />


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

      <Dialog v-model:open="showDeleteDialog">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Hapus data ini?</DialogTitle>
            <DialogDescription>
              Anda akan menghapus user <b>{{ rowToDelete?.name }}</b>. Tindakan ini tidak dapat dibatalkan.
            </DialogDescription>
          </DialogHeader>
          <DialogFooter>
            <Button variant="outline" @click="showDeleteDialog = false">Batal</Button>
            <Button variant="destructive" @click="confirmDeleteRow">Hapus</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>
