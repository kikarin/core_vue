<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
  users: Array<{
    id: number
    name: string
    email: string
    role: string
    is_active: boolean
  }>
  total: number
  currentPage: number
  perPage: number
  search: string
  sort: string
  order: string
}>()

const breadcrumbs = [
  { title: 'Users', href: '/users' },
]

const columns = [
  { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
  { key: 'email', label: 'Email', searchable: true, orderable: true, visible: true },
  { key: 'role', label: 'Role', searchable: true, orderable: true, visible: true },
  { 
    key: 'is_active', 
    label: 'Status', 
    searchable: false, 
    orderable: true, 
    visible: true,
    format: (row: any) => {
      return row.is_active ? 
        '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>' : 
        '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>';
    }
  },
]

const actions = (row: any) => [
  {
    label: 'Detail',
    onClick: () => router.visit(`/users/${row.id}`)
  },
  {
    label: 'Edit',
    onClick: () => router.visit(`/users/${row.id}/edit`)
  },
  {
    label: 'Delete',
    onClick: () => {
      if (confirm(`Delete user "${row.name}"?`)) {
        router.delete(`/users/${row.id}`)
      }
    }
  }
]

const selected = ref<number[]>([])

const deleteSelected = () => {
  if (confirm(`Are you sure you want to delete ${selected.value.length} items?`)) {
    router.delete('/users/delete-selected', {
      data: { ids: selected.value },
      onSuccess: () => {
        selected.value = []
      }
    })
  }
}

const handleSearch = (params: any) => {
  router.get('/users', params, {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="space-y-4">
    <PageIndex
      title="Users"
      :breadcrumbs="breadcrumbs"
      :columns="columns"
      :rows="users"
      :actions="actions"
      create-url="/users/create"
      :selected="selected"
      :on-delete-selected="deleteSelected"
      :total="total"
      :current-page="currentPage"
      :per-page="perPage"
      :search="search"
      :sort="sort"
      :order="order"
      @search="handleSearch"
    />
  </div>
</template>
