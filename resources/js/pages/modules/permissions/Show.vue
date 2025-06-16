<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'

const props = defineProps<{
  item: Record<string, any>
}>()

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Permissions', href: '/menu-permissions/permissions' },
  { title: 'Detail Category', href: '#' },
]

const fields = [
  { label: 'Name', value: props.item?.name || '-' },
  { label: 'Sequence', value: props.item?.sequence || '-' },
]

const actionFields = [
  { label: 'Created At', value: props.item?.created_at || '-' },
  { label: 'Created By', value: props.item?.created_by_user?.name || '-' },
  { label: 'Updated At', value: props.item?.updated_at || '-' },
  { label: 'Updated By', value: props.item?.updated_by_user?.name || '-' },
]
</script>

<template>
  <PageShow
    title="Permission Category"
    :breadcrumbs="breadcrumbs"
    :fields="fields"
    :action-fields="actionFields"
    back-url="/menu-permissions/permissions"
  />
</template>
