<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PermissionCard from '@/pages/modules/components/permissions/PermissionCard.vue'
import { Button } from '@/components/ui/button'

defineProps<{
  categories: Array<{
    id: number
    name: string
    permissions: Array<{ id: number; name: string }>
  }>
}>()

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Permissions', href: '/menu-permissions/permissions' },
]
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Permissions</h1>
        <Button @click="router.visit('/menu-permissions/permissions/create')">
          + Create Category
        </Button>
      </div>
      <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
        <PermissionCard v-for="category in categories" :key="category.id" :category="category" />
      </div>
    </div>
  </AppLayout>
</template>
