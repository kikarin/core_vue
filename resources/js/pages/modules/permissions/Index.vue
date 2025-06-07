<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PermissionCard from '@/pages/modules/components/permissions/PermissionCard.vue'
import { Button } from '@/components/ui/button'

const breadcrumbs = [
    { title: 'Menu & Permissions', href: '#' },
    { title: 'Permissions', href: '/menu-permissions/permissions' },
]

const categories = [
    {
        id: 1,
        name: 'Users',
        permissions: [
            { id: 1, name: 'Users Show' },
            { id: 2, name: 'Users Create' },
            { id: 3, name: 'Users Edit' },
            { id: 4, name: 'Users Detail' },
            { id: 5, name: 'Users Delete' },
        ],
    },
    {
        id: 2,
        name: 'Teams',
        permissions: [
            { id: 6, name: 'Teams View' },
            { id: 7, name: 'Teams Create' },
        ],
    },
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

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
      <PermissionCard v-for="category in categories" :key="category.id" :category="category" />
    </div>
  </div>
</AppLayout>

</template>
