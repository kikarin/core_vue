<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  item: {
    id: number
    name: string
    bg: string
    init_page_login: string
    is_allow_login: string
    is_vertical_menu: string
    created_at: string
    updated_at: string
  }
}>()

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Roles', href: '/menu-permissions/roles' },
  { title: 'Detail Role', href: `/menu-permissions/roles/${props.item.id}` },
]

const fields = [
  { label: 'Name', value: props.item.name },
  { label: 'BG', value: props.item.bg },
  { label: 'Default Page', value: props.item.init_page_login },
  { label: 'Can Login', value: props.item.is_allow_login },
  { label: 'Menu Type', value: props.item.is_vertical_menu },
]

const actionFields = [
  { label: 'Created At', value: props.item.created_at },
  { label: 'Updated At', value: props.item.updated_at },
]

const handleEdit = () => {
  router.visit(`/menu-permissions/roles/${props.item.id}/edit`)
}
const handleDelete = () => {
  if (confirm('Are you sure you want to delete this role?')) {
    router.delete(`/menu-permissions/roles/${props.item.id}`)
  }
}
</script>

<template>
  <PageShow
    title="Role"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :action-fields="actionFields"
    :back-url="'/menu-permissions/roles'"
    :on-edit="handleEdit"
    :on-delete="handleDelete"
  />
</template>
