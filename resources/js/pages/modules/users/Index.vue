<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'
import { ref, getCurrentInstance } from 'vue'

const breadcrumbs = [
  { title: 'Users', href: '/users' },
]

const columns = [
  { key: 'name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'role', label: 'Role' },
  {
    key: 'is_active',
    label: 'Status',
    format: (row: any) => {
      return row.is_active
        ? '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>'
        : '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>'
    },
  },
]

const selected = ref<number[]>([])

const { emit } = getCurrentInstance()!

const pageIndex = ref()

const handleDeleteRow = (row: any) => {
  
}

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
    onClick: () => pageIndex.value.handleDeleteRow(row)
  }
]

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
</script>

<template>
  <div class="space-y-4">
    <PageIndex
      title="Users"
      :breadcrumbs="breadcrumbs"
      :columns="columns"
      :create-url="'/users/create'"
      :actions="actions"
      :selected="selected"
      :on-delete-selected="deleteSelected"
      api-endpoint="/api/users"
      ref="pageIndex"
    />
  </div>
</template>
