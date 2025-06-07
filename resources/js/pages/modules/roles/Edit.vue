<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue'
import Form from './Form.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Roles', href: '/menu-permissions/roles' },
  { title: 'Edit Role', href: '/menu-permissions/roles/edit' },
]

const initialData = {
  id: 1,
  name: 'Admin',
  default_page: '/dashboard',
  can_login: 'ya',
}
</script>

<template>
  <PageEdit title="Role" :breadcrumbs="breadcrumbs" back-url="/menu-permissions/roles">
    <Form mode="edit" :initial-data="initialData" />
  </PageEdit>
</template>
