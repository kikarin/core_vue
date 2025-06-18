<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useToast } from '@/components/ui/toast/useToast'
import axios from 'axios'
import { getCurrentInstance as vueGetCurrentInstance } from 'vue'


const breadcrumbs = [
    { title: 'Menu & Permissions', href: '#' },
    { title: 'Roles', href: '/menu-permissions/roles' },
]

const columns = [
    { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
    { key: 'init_page_login', label: 'Default Page', searchable: true, orderable: true, visible: true },
    { key: 'is_allow_login', label: 'Can Login', searchable: false, orderable: true, visible: true },
]

const selected = ref<number[]>([])

const { emit } = getCurrentInstance()!

const pageIndex = ref()

const { toast } = useToast()

const actions = (row: any) => [
    {
        label: 'Detail',
        onClick: () => router.visit(`/menu-permissions/roles/${row.id}`)
    },
    {
        label: 'Edit',
        onClick: () => router.visit(`/menu-permissions/roles/${row.id}/edit`)
    },
    {
        label: 'Delete',
        onClick: () => pageIndex.value.handleDeleteRow(row)
    },
    {
        label: 'Set Permissions',
        onClick: () => router.visit(`/menu-permissions/roles/set-permissions/${row.id}`)
    }
]

function getCurrentInstance() {
  const instance = vueGetCurrentInstance()
  if (!instance) throw new Error('getCurrentInstance must be called within setup')
  return instance
}

const deleteSelected = async () => {
  if (!selected.value.length) return toast({ title: 'Pilih data yang akan dihapus', variant: 'destructive' })
  try {
    await axios.post('/menu-permissions/roles/destroy-selected', {
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

const deleteRole = async (row: any) => {
  await router.delete(`/menu-permissions/roles/${row.id}`, {
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
        title="Roles"
        :breadcrumbs="breadcrumbs"
        :columns="columns"
        :create-url="'/menu-permissions/roles/create'"
        :actions="actions"
        :selected="selected"
        @update:selected="val => selected = val"
        :on-delete-selected="deleteSelected"
        api-endpoint="/api/roles"
        ref="pageIndex"
        :on-toast="toast"
        :on-delete-row-confirm="deleteRole"
    />
</template>
