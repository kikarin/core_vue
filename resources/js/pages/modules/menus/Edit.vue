<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue'
import Form from './Form.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Menus', href: '/menu-permissions/menus' },
  { title: 'Edit Menu', href: '/menu-permissions/menus/edit' },
]

const initialData = {
  id: 1,
  name: 'Dashboard',
  code: 'dashboard',
  icon: 'LayoutGrid',
  parent: '',
  permission: 'view dashboard',
  url: '/dashboard',
  order: 1,
}
</script>

<template>
  <PageEdit title="Menu" :breadcrumbs="breadcrumbs" back-url="/menu-permissions/menus">
    <Form mode="edit" :initial-data="initialData" />
  </PageEdit>
</template>
