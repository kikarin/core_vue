<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Management', href: '#' },
  { title: 'Teams', href: '/management/members' },
  { title: 'Members', href: '/management/members' },
]

const columns = [
  { key: 'name', label: 'Nama' },
  { key: 'age', label: 'Umur' },
  { key: 'team', label: 'Team' },
]

const rows = [
  { 
    id: 1, 
    name: 'John Doe', 
    age: 25,
    team: 'Team A',
  },
  { 
    id: 2, 
    name: 'Jane Smith', 
    age: 30,
    team: 'Team B',
  },
]

const actions = (row: any) => [
  {
    label: 'Detail',
    onClick: () => {
      router.visit(`/management/members/${row.id}`)
    },
  },
  {
    label: 'Edit',
    onClick: () => {
      router.visit(`/management/members/${row.id}/edit`)
    },
  },
  {
    label: 'Delete',
    onClick: () => {
      if (confirm(`Apakah Anda yakin ingin menghapus member ${row.name}?`)) {
        router.delete(`/management/members/${row.id}`)
      }
    },
  },
]
</script>

<template>
<PageIndex
  title="Members"
  :breadcrumbs="breadcrumbs"
  :columns="columns"
  :rows="rows"
  :actions="actions"
  create-url="/management/members/create"
/>
</template> 