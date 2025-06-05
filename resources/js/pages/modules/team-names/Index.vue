<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Data Master', href: '/data-master' },
  { title: 'Nama Team', href: '/data-master/team-names' },
]

const columns = [
  { key: 'name', label: 'Nama Team' },
]

const rows = [
  { 
    id: 1, 
    name: 'Team A', 
  },
  { 
    id: 2, 
    name: 'Team B', 
  },
  { 
    id: 3, 
    name: 'Team C', 
  },
]

const actions = (row: any) => [
  {
    label: 'Detail',
    onClick: () => {
      router.visit(`/data-master/team-names/${row.id}`)
    },
  },
  {
    label: 'Edit',
    onClick: () => {
      router.visit(`/data-master/team-names/${row.id}/edit`)
    },
  },
  {
    label: 'Delete',
    onClick: () => {
      if (confirm(`Apakah Anda yakin ingin menghapus team ${row.name}?`)) {
        router.delete(`/data-master/team-names/${row.id}`)
      }
    },
  },
]
</script>

<template>
<PageIndex
  title="Nama Team"
  :breadcrumbs="breadcrumbs"
  :columns="columns"
  :rows="rows"
  :actions="actions"
  create-url="/data-master/team-names/create"
/>
</template> 