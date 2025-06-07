<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue'
import Form from './Form.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Permissions', href: '/menu-permissions/permissions' },
  { title: 'Edit Category', href: '#' },
]

const initialData = {
  id: 1,
  name: 'Users',
  sequence: 1,
}
</script>

<template>
  <PageEdit title="Permission Category" :breadcrumbs="breadcrumbs" back-url="/menu-permissions/permissions">
    <Form mode="edit" :initial-data="initialData" />
  </PageEdit>
</template>
