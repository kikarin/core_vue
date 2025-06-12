<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PermissionTree from '@/pages/modules/roles/PermissionTree.vue'
import { Button } from '@/components/ui/button'
import { ArrowLeft } from 'lucide-vue-next'

const props = defineProps<{
  item: Record<string, any>,
  permissionGroups: Array<{ label: string, children: Array<{ id: number, label: string }> }>,
  selectedPermissions: number[],
}>()

const roleId = props.item.id
const selectedPermissions = ref<number[]>([...props.selectedPermissions])

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Roles', href: '/menu-permissions/roles' },
  { title: 'Set Permissions', href: '#' },
]

const savePermissions = () => {
  router.post(`/menu-permissions/roles/set-permissions/${roleId}`, {
    id: roleId,
    permission_id: selectedPermissions.value,
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
        <span class="text-foreground">{{ props.item.name }}</span>
      </div>

      <!-- Save Button -->
      <div class="flex justify-center">
        <Button @click="savePermissions">Save</Button>
      </div>

      <!-- Permissions -->
      <PermissionTree :groups="props.permissionGroups" v-model="selectedPermissions" />

    </div>
  </AppLayout>
</template>
