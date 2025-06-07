<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  mode: 'create' | 'edit'
  initialData?: Record<string, any>
}>()

const formInputs = [
  { name: 'name', label: 'Name', type: 'text' as const, placeholder: 'Menu name', required: true },
  { name: 'code', label: 'Code', type: 'text' as const, placeholder: 'Unique code', required: true },
  { name: 'icon', label: 'Icon', type: 'text' as const, placeholder: 'Lucide icon name', required: false },
  {
    name: 'parent',
    label: 'Parent Menu',
    type: 'select' as const,
    placeholder: 'Choose parent',
    required: false,
    options: [
      { value: 'none', label: '-' },
      { value: 'management', label: 'Management' },
    ],
  },
  {
    name: 'permission',
    label: 'Permission',
    type: 'select' as const,
    placeholder: 'Choose permission',
    required: true,
    options: [
      { value: 'view dashboard', label: 'View Dashboard' },
      { value: 'manage users', label: 'Manage Users' },
    ],
  },
  { name: 'url', label: 'URL', type: 'text' as const, placeholder: '/example/path', required: true },
  { name: 'order', label: 'Order', type: 'text' as const, placeholder: '1, 2, 3...', required: true },
]

const handleSave = (data: Record<string, any>) => {
  if (props.mode === 'create') {
    router.post('/menu-permissions/menus', data)
  } else {
    router.put(`/menu-permissions/menus/${props.initialData?.id}`, data)
  }
}
</script>

<template>
  <FormInput :form-inputs="formInputs" @save="handleSave" />
</template>
