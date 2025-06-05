<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Management', href: '#' },
  { title: 'Teams', href: '/management/teams' },
  { title: 'List Team', href: '/management/teams' },
]

const columns = [
  { key: 'name', label: 'Team Name' },
  { key: 'description', label: 'Description' },
  { key: 'members', label: 'Members' },
]

const rows = [
  { 
    id: 1, 
    name: 'Team A', 
    description: 'Main development team for core features',
    members: '1 members',
  },
  { 
    id: 2, 
    name: 'Team B', 
    description: 'UI/UX and graphic design team',
    members: '1 members',
  },
]

const actions = (row: any) => [
  {
    label: 'Detail',
    onClick: () => {
      router.visit(`/management/teams/${row.id}`)
    },
  },
  {
    label: 'Edit',
    onClick: () => {
      router.visit(`/management/teams/${row.id}/edit`)
    },
  },
  {
    label: 'Delete',
    onClick: () => {
      if (confirm(`Are you sure you want to delete team ${row.name}?`)) {
        router.delete(`/management/teams/${row.id}`)
      }
    },
  },
]
</script>

<template>
<PageIndex
  title="Teams"
  :breadcrumbs="breadcrumbs"
  :columns="columns"
  :rows="rows"
  :actions="actions"
  create-url="/management/teams/create"
/>
</template> 