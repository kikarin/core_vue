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
    label: 'Nama Team',
    type: 'text' as const,
    placeholder: 'Masukkan nama team',
    required: true,
  },
]

const handleSave = (data: Record<string, any>) => {
  if (props.mode === 'create') {
    router.post('/data-master/team-names', data)
  } else {
    router.put(`/data-master/team-names/${props.initialData?.id}`, data)
  }
}


</script>

<template>
  <FormInput
    :form-inputs="formInputs"
    @save="handleSave"
  />
</template> 