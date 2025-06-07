<script setup lang="ts">
import PageCreate from '@/pages/modules/base-page/PageCreate.vue'
import PermissionForm from './PermissionForm.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Permissions', href: '/menu-permissions/permissions' },
  { title: 'Add Permission', href: '#' },
]

const query = new URLSearchParams(window.location.search)
const categoryId = Number(query.get('category_id') || 1)

const initialData = {
  category_id: categoryId,
  name: '',
  code: '',
  sequence: 1,
}
</script>

<template>
  <PageCreate title="Add Permission" :breadcrumbs="breadcrumbs" back-url="/menu-permissions/permissions">
    <PermissionForm mode="create" :initial-data="initialData" />
  </PageCreate>
</template>
