<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import PageCreate from '@/pages/modules/base-page/PageCreate.vue'
import PermissionTree from '@/components/permissions/PermissionTree.vue'

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Roles', href: '/menu-permissions/roles' },
  { title: 'Set Permissions', href: '#' },
]

const selectedPermissions = ref<number[]>([])

const permissionGroups = [
  {
    label: 'Users',
    children: [
      { id: 1, label: 'View Users' },
      { id: 2, label: 'Create Users' },
      { id: 3, label: 'Edit Users' },
      { id: 4, label: 'Delete Users' },
    ],
  },
  {
    label: 'Teams',
    children: [
      { id: 5, label: 'View Teams' },
      { id: 6, label: 'Create Teams' },
    ],
  },
]

const savePermissions = () => {
  console.log('Selected IDs:', selectedPermissions.value)
  router.post('/menu-permissions/roles/set-permissions', {
    permissions: selectedPermissions.value,
  })
}

const goBack = () => {
  router.visit('/menu-permissions/roles')
}
</script>

<template>
  <PageCreate title="Set Permissions" :breadcrumbs="breadcrumbs" back-url="/menu-permissions/roles">
    <div class="bg-white dark:bg-background border rounded-lg shadow-sm p-6 space-y-6">
      <PermissionTree
        :groups="permissionGroups"
        v-model="selectedPermissions"
      />

      <div class="flex justify-end gap-2">
        <button
          class="px-4 py-2 border rounded-md text-sm hover:bg-muted"
          @click="goBack"
        >
          Back
        </button>
        <button
          class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-primary/90"
          @click="savePermissions"
        >
          Save
        </button>
      </div>
    </div>
  </PageCreate>
</template>
