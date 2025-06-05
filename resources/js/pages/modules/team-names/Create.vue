<script setup lang="ts">
import PageCreate from '@/pages/modules/base-page/PageCreate.vue'
import Form from './Form.vue'

const breadcrumbs = [
  { title: 'Data Master', href: '/data-master' },
  { title: 'Nama Team', href: '/data-master/team-names' },
  { title: 'Create Team', href: '/data-master/team-names/create' },
]
</script>

<template>
  <PageCreate
    title="Tambah Team"
    :breadcrumbs="breadcrumbs"
  >
    <Form mode="create" />
  </PageCreate>
</template> 