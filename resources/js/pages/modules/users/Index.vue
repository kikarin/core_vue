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
    status: string
  }>
}>()

const breadcrumbs = [
  { title: 'Users', href: '/users' },
]

const columns = [
  { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
  { key: 'email', label: 'Email', searchable: true, orderable: true, visible: true },
  { key: 'role', label: 'Role', searchable: false, orderable: false, visible: true },
  {
    key: 'status',
    label: 'Status',
    className: 'text-center',
    searchable: false,
    orderable: true,
    visible: true,
    format: (row: any) => {
      const isActive = row.status === 'Active';
      return `<span class="${isActive ? 'text-green-600' : 'text-red-600'}">${row.status}</span>`;
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
      if (confirm(`Are you sure you want to delete ${row.name}?`)) {
        router.delete(`/users/${row.id}`)
      }
    }
  }
]

const selected = ref<number[]>([])

const deleteSelected = () => {
  if (confirm(`Are you sure you want to delete ${selected.value.length} items?`)) {
    router.delete(route('users.destroy-selected'), {
      data: { id: selected.value },
      onSuccess: () => {
        selected.value = [];
      }
    });
  }
};


</script>

<template>
  <PageIndex title="Users" :breadcrumbs="breadcrumbs" :columns="columns" :rows="props.users" :actions="actions"
    create-url="/users/create" destroy-selected-url="/users/destroy-selected" />
</template>
