<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  mode: 'create' | 'edit'
  initialData?: Record<string, any>
  get_CategoryPermission?: Record<number, string>
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
  ...(props.get_CategoryPermission ? [
    {
      name: 'category_permission_id',
      label: 'Category Permission',
      type: 'select' as const,
      options: Object.entries(props.get_CategoryPermission).map(([id, name]) => ({ value: Number(id), label: name })),
      required: true,
      placeholder: 'Pilih Kategori',
    }
  ] : [])
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
