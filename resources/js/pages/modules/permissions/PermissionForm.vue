<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps<{
  mode: 'create' | 'edit'
  initialData?: Record<string, any>
  categoryId?: number
  onBack?: () => void
}>()

const formInputs = [
  {
    name: 'name',
    label: 'Permission Name',
    type: 'text' as const,
    placeholder: 'Users Create',
    required: true,
  },

]

const handleSave = (data: Record<string, any>) => {
  if (props.mode === 'create') {
    router.post('/menu-permissions/permissions/store-permission', data)
  } else {
    router.put(`/menu-permissions/permissions/update-permission/${props.initialData?.id}`, data)
  }
}
</script>

<template>
  <FormInput
    :form-inputs="formInputs"
    :initial-data="initialData"
    @save="handleSave"
    @cancel="props.onBack"
  />
</template>
