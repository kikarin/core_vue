<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue'
import Form from './Form.vue'

const breadcrumbs = [
  { title: 'Management', href: '#' },
  { title: 'Teams', href: '/management/teams' },
  { title: 'List Team', href: '/management/teams' },
  { title: 'Edit Team', href: '/management/teams/edit' },
]

const initialData = {
  id: 1,
  name: 'Development Team',
  description: 'Main development team for core features',
  status: 'Active',
}
</script>

<template>
  <PageEdit
    title="Team"
    :breadcrumbs="breadcrumbs"
    back-url="/management/teams"
  >
    <Form mode="edit" :initial-data="initialData" />
  </PageEdit>
</template> 