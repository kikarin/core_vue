<script setup lang="ts">
import PageCreate from '@/pages/modules/base-page/PageCreate.vue'
import Form from './Form.vue'

const breadcrumbs = [
  { title: 'Management', href: '#' },
  { title: 'Teams', href: '/management/teams' },
  { title: 'List Teams', href: '/management/teams' },
  { title: 'Create Team', href: '/management/teams/create' },
]
</script>

<template>
  <PageCreate
    title="Create Team"
    :breadcrumbs="breadcrumbs"
    back-url="/management/teams"
  >
    <Form mode="create" />
  </PageCreate>
</template> 