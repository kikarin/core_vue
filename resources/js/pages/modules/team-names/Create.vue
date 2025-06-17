<script setup lang="ts">
import PageCreate from '@/pages/modules/base-page/PageCreate.vue'
import Form from './Form.vue'

const props = defineProps<{
  listBg?: Record<string, string>
  listInitPage?: Record<string, string>
}>()

const breadcrumbs = [
  { title: 'Data Master', href: '/data-master' },
  { title: 'Nama Team', href: '/data-master/team-names' },
  { title: 'Create Team', href: '/data-master/team-names/create' },
]
</script>

<template>
  <PageCreate
    title="Create Team"
    :breadcrumbs="breadcrumbs"
    back-url="/data-master/team-names"
  >
    <Form mode="create" />
  </PageCreate>
</template> 