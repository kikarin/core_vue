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

console.log('SetPermissions.vue props.permissionGroups:', props.permissionGroups)
console.log('SetPermissions.vue props.selectedPermissions:', props.selectedPermissions)

const roleId = props.item.id
const selectedPermissions = ref<number[]>([...props.selectedPermissions])
const loading = ref(false)
const success = ref(false)
const error = ref('')

const breadcrumbs = [
  { title: 'Menu & Permissions', href: '#' },
  { title: 'Roles', href: '/menu-permissions/roles' },
  { title: 'Set Permissions', href: '#' },
]

const savePermissions = async () => {
  loading.value = true
  success.value = false
  error.value = ''
  await router.post(`/menu-permissions/roles/set-permissions/${roleId}`, {
    id: roleId,
    permission_id: selectedPermissions.value,
  }, {
    onSuccess: () => { success.value = true },
    onError: (e) => { error.value = 'Gagal menyimpan permission.' },
    onFinish: () => { loading.value = false },
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
      <div class="flex justify-center gap-2">
        <Button :disabled="loading" @click="savePermissions">
          <span v-if="loading">Saving...</span>
          <span v-else>Save</span>
        </Button>
        <span v-if="success" class="text-green-600 font-semibold">Berhasil disimpan!</span>
        <span v-if="error" class="text-red-600 font-semibold">{{ error }}</span>
      </div>

      <!-- Permissions -->
      <PermissionTree :groups="props.permissionGroups" v-model="selectedPermissions" />

    </div>
  </AppLayout>
</template>
