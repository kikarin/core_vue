<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  mode: 'create' | 'edit'
  initialData?: Record<string, any>
  onBack?: () => void
}>()

const formInputs = [
  {
    name: 'name',
    label: 'Name Permission Category',
    type: 'text' as const,
    placeholder: 'Enter permission category name',
    required: true,
  },
  {
    name: 'sequence',
    label: 'Sequence',
    type: 'text' as const,
    placeholder: 'Enter display order',
    required: true,
  },
]

const handleSave = (data: Record<string, any>) => {
  if (props.mode === 'create') {
    router.post('/menu-permissions/permissions', data)
  } else {
    router.put(`/menu-permissions/permissions/${props.initialData?.id}`, data)
  }
}
</script>

<template>
  <FormInput :form-inputs="formInputs" @save="handleSave" @cancel="props.onBack" />
</template>
