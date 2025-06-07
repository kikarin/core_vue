<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  mode: 'create' | 'edit'
  initialData?: Record<string, any>
}>()

const formInputs = [
  {
    name: 'name',
    label: 'Name',
    type: 'text' as const,
    placeholder: 'Enter role name',
    required: true,
  },
  {
    name: 'default_page',
    label: 'Default Page',
    type: 'select' as const,
    placeholder: 'Select default page',
    required: true,
    options: [
      { value: '/dashboard', label: 'Dashboard' },
      { value: '/management/users', label: 'Users' },
    ],
  },
  {
    name: 'can_login',
    label: 'Can Login',
    type: 'radio' as const,
    required: true,
    options: [
      { value: 'ya', label: 'Ya' },
      { value: 'tidak', label: 'Tidak' },
    ],
  },
]

const handleSave = (data: Record<string, any>) => {
  if (props.mode === 'create') {
    router.post('/menu-permissions/roles', data)
  } else {
    router.put(`/menu-permissions/roles/${props.initialData?.id}`, data)
  }
}
</script>

<template>
  <FormInput :form-inputs="formInputs" :initial-data="initialData" @save="handleSave" />
</template>
