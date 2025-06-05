<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'

const breadcrumbs = [
  { title: 'Management', href: '#' },
  { title: 'Teams', href: '/management/teams' },
  { title: 'List Team', href: '/management/teams' },
  { title: 'Team Detail', href: '/management/teams/show' },
]

const fields = [
  { label: 'Team Name', value: 'Team A' },
  { label: 'Description', value: 'Main development team for core features' },
  { label: 'Members', value: '1 members' },
  { label: 'Created At', value: '2025-03-20' },
  { label: 'Updated At', value: '2025-03-20' },
]
</script>

<template>
<PageShow
  title="Team Details"
  :breadcrumbs="breadcrumbs"
  :fields="fields"
  edit-url="/management/teams/edit"
/>
</template> 