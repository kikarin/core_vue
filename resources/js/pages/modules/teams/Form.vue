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
    label: 'Team Name',
    type: 'text' as const,
    placeholder: 'Enter team name',
    required: true,
  },
  {
    name: 'description',
    label: 'Description',
    type: 'textarea' as const,
    placeholder: 'Enter team description',
    required: true,
  },
]

const handleSave = (data: Record<string, any>) => {
  if (props.mode === 'create') {
    router.post('/management/teams', data)
  } else {
    router.put(`/management/teams/${props.initialData?.id}`, data)
  }
}

</script>

<template>
  <FormInput
    :form-inputs="formInputs"
    @save="handleSave"
  />
</template> 