<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'
import { ref, getCurrentInstance } from 'vue'
import { useToast } from '@/components/ui/toast/useToast'
import axios from 'axios'

const props = defineProps<{
  teams: Array<{ id: number, nama: string }>,
  meta?: Record<string, any>
}>()

const breadcrumbs = [
  { title: 'Data Master', href: '/data-master' },
  { title: 'Nama Team', href: '/data-master/team-names' },
]

const columns = [
  { key: 'nama', label: 'Nama Team' },
]

const selected = ref<number[]>([])

const { emit } = getCurrentInstance()!

const pageIndex = ref()

const { toast } = useToast()

const actions = (row: any) => [
  {
    label: 'Detail',
    onClick: () => router.visit(`/data-master/team-names/${row.id}`)
  },
  {
    label: 'Edit',
    onClick: () => router.visit(`/data-master/team-names/${row.id}/edit`)
  },
  {
    label: 'Delete',
    onClick: () => {
      if (confirm(`Apakah Anda yakin ingin menghapus team ${row.nama}?`)) {
        router.delete(`/data-master/team-names/${row.id}`)
      }
    }
  }
]

const deleteSelected = async () => {
  if (!selected.value.length) return toast({ title: 'Pilih data yang akan dihapus', variant: 'destructive' })
  try {
    await axios.post('/team-names/destroy-selected', {
      ids: selected.value,
    })
    selected.value = []
    pageIndex.value.fetchData()
    toast({ title: 'Data berhasil dihapus', variant: 'success' })
  } catch (error) {
    console.error('Gagal menghapus data:', error)
    toast({ title: 'Gagal menghapus data yang dipilih.', variant: 'destructive' })
  }
}

const deleteTeam = async (row: any) => {
  await router.delete(`/data-master/team-names/${row.id}`, {
    onSuccess: () => {
      toast({ title: 'Data berhasil dihapus', variant: 'success' })
      pageIndex.value.fetchData()
    },
    onError: () => {
      toast({ title: 'Gagal menghapus data.', variant: 'destructive' })
    }
  })
}
</script>

<template>
    <PageIndex
      title="Team Name"
      :breadcrumbs="breadcrumbs"
      :columns="columns"
      :create-url="'/data-master/team-names/create'"
      :actions="actions"
      :selected="selected"
      @update:selected="val => selected = val"
      :on-delete-selected="deleteSelected"
      :data="props.teams"
      :meta="props.meta"
      api-endpoint="/api/team-names"
      ref="pageIndex"
      :on-toast="toast"
      :on-delete-row="deleteTeam"
    />
</template> 