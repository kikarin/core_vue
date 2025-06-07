<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue'
import PermissionForm from './PermissionForm.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Permissions', href: '/menu-permissions/permissions' },
  { title: 'Edit Permission', href: '#' },
]

const initialData = {
  id: 5,
  name: 'Users Edit',
  sequence: 3,
  category_id: 1,
}
</script>

<template>
  <PageEdit title="Permission" :breadcrumbs="breadcrumbs" back-url="/menu-permissions/permissions">
    <PermissionForm mode="edit" :initial-data="initialData" />
  </PageEdit>
</template>
