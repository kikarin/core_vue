<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue'
import Form from './Form.vue'
import { ref } from 'vue'

const breadcrumbs = [
  { title: 'Data Master', href: '/data-master' },
  { title: 'Nama Team', href: '/data-master/team-names' },
  { title: 'Edit Team', href: '/data-master/team-names/1/edit' },
]

const initialData = ref({
  id: 1,
  name: 'Team A',
  description: 'Tim Pengembangan A',
  status: 'active'
})
</script>

<template>
  <PageEdit
    title="Team"
    :breadcrumbs="breadcrumbs"
    back-url="/data-master/team-names"
  >
    <Form mode="edit" :initial-data="initialData" />
  </PageEdit>
</template> 