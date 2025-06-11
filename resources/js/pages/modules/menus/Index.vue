<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'
import * as LucideIcons from 'lucide-vue-next'
import { ref } from 'vue'

const props = defineProps<{
  menus: Array<{
    id: number
    name: string
    code: string
    icon: string
    parent: string
    permission: string
    url: string
    order: number
  }>
}>()

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Menus', href: '/menu-permissions/menus' },
]

const columns = [
  { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
  { key: 'code', label: 'Code', searchable: true, orderable: true, visible: true },
  { key: 'icon', label: 'Icon', searchable: false, orderable: false, visible: true },
  { 
    key: 'parent', 
    label: 'Parent', 
    searchable: false, 
    orderable: true, 
    visible: true,
    format: (row: any) => {
      return row.parent === '-' ? 'Menu Utama' : row.parent
    }
  },
  { key: 'url', label: 'URL', searchable: true, orderable: true, visible: true },
]

const actions = (row: any) => [
  {
    label: 'Detail',
    onClick: () => router.visit(`/menu-permissions/menus/${row.id}`),
  },
  {
    label: 'Edit',
    onClick: () => router.visit(`/menu-permissions/menus/${row.id}/edit`),
  },
  {
    label: 'Delete',
    onClick: () => {
      if (confirm(`Delete menu "${row.name}"?`)) {
        router.delete(`/menu-permissions/menus/${row.id}`)
      }
    },
  },
]

const selected = ref<number[]>([])

const deleteSelected = () => {
  if (confirm(`Are you sure you want to delete ${selected.value.length} items?`)) {
    router.delete(route('menu-permissions.menus.destroy-selected'), {
      data: { array_id: selected.value },
      onSuccess: () => {
        selected.value = [];
      }
    });
  }
};

const handleSearch = (params: any) => {
  router.get(route('menu-permissions.menus.index'), params, {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <PageIndex
    title="Menus"
    :breadcrumbs="breadcrumbs"
    :columns="columns"
    :rows="props.menus"
    :actions="actions"
    create-url="/menu-permissions/menus/create"
    :selected="selected"
    :on-delete-selected="deleteSelected"
    @search="handleSearch"
  />
</template>
