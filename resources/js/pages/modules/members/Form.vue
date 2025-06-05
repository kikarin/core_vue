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
    label: 'Nama',
    type: 'text',
    placeholder: 'Masukkan nama',
    required: true,
  },
  {
    name: 'age',
    label: 'Umur',
    type: 'number',
    placeholder: 'Masukkan umur',
    required: true,
  },
  {
    name: 'team_id',
    label: 'Team',
    type: 'select',
    placeholder: 'Pilih team',
    required: true,
    options: [
      { value: 1, label: 'Team A' },
      { value: 2, label: 'Team B' },
      { value: 3, label: 'Team C' },
    ],
  },
]

const handleSave = (data: Record<string, any>) => {
  if (props.mode === 'create') {
    router.post('/management/members', data)
  } else {
    router.put(`/management/members/${props.initialData?.id}`, data)
  }
}


</script>

<template>
  <FormInput
    :form-inputs="formInputs"
    @save="handleSave"
  />
</template> 