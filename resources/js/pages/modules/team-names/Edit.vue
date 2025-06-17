<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue'
import Form from './Form.vue'

const props = defineProps<{
  item: { id: number, nama: string }
}>()

const breadcrumbs = [
  { title: 'Data Master', href: '/data-master' },
  { title: 'Nama Team', href: '/data-master/team-names' },
  { title: 'Edit Team', href: `/data-master/team-names/${props.item.id}/edit` },
]
</script>

<template>
  <PageEdit
    title="Team"
    :breadcrumbs="breadcrumbs"
    back-url="/data-master/team-names"
  >
    <Form mode="edit" :initial-data="props.item" />
  </PageEdit>
</template> 