<script setup lang="ts">
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PermissionTree from '@/pages/modules/roles/PermissionTree.vue'
import { Button } from '@/components/ui/button'
import { ArrowLeft } from 'lucide-vue-next'

const page = usePage()
const roleId = page.props.id

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
  router.post(`/menu-permissions/roles/set-permissions/${roleId}`, {
    permissions: selectedPermissions.value,
  })
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-6">

      <!-- Back Button -->
      <div>
        <Button variant="secondary" @click="router.visit('/menu-permissions/roles')">
          <ArrowLeft class="w-4 h-4 mr-2" />
          Back
        </Button>
      </div>

      <!-- Role Name -->
      <div class="border px-4 py-3 rounded bg-muted font-medium text-center">
        <span>Name: </span>
        <span class="text-foreground">Super Admin</span>
      </div>

      <!-- Save Button -->
      <div class="flex justify-center">
        <Button @click="savePermissions">Save</Button>
      </div>

      <!-- Permissions -->
      <PermissionTree :groups="permissionGroups" v-model="selectedPermissions" />

    </div>
  </AppLayout>
</template>
