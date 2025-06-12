<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  mode: 'create' | 'edit',
  initialData?: Record<string, any>,
  listBg: Record<string, string>,
  listInitPage: Record<string, string>,
}>()

const bgOptions = Object.entries(props.listBg).map(([value, label]) => ({ value, label }))
const initPageOptions = Object.entries(props.listInitPage).map(([value, label]) => ({ value, label }))

const formInputs = [
  {
    name: 'name',
    label: 'Name',
    type: 'text' as const,
    placeholder: 'Enter role name',
    required: true,
  },
  {
    name: 'bg',
    label: 'BG',
    type: 'select' as const,
    placeholder: 'Select background',
    required: false,
    options: bgOptions,
  },
  {
    name: 'init_page_login',
    label: 'Default Page',
    type: 'select' as const,
    placeholder: 'Select default page',
    required: true,
    options: initPageOptions,
  },
  {
    name: 'is_allow_login',
    label: 'Can Login',
    type: 'radio' as const,
    required: true,
    options: [
      { value: '1', label: 'Ya' },
      { value: '0', label: 'Tidak' },
    ],
  },
  {
    name: 'is_vertical_menu',
    label: 'Menu Type',
    type: 'radio' as const,
    required: true,
    options: [
      { value: '1', label: 'Vertical' },
      { value: '0', label: 'Horizontal' },
    ],
  },
]

const handleSave = (data: Record<string, any>) => {
  const formData = {
    ...data,
    is_allow_login: data.is_allow_login === '1',
    is_vertical_menu: data.is_vertical_menu === '1',
  }
  if (props.mode === 'create') {
    router.post('/menu-permissions/roles', formData)
  } else {
    router.put(`/menu-permissions/roles/${props.initialData?.id}`, formData)
  }
}
</script>

<template>
  <FormInput :form-inputs="formInputs" :initial-data="initialData" @save="handleSave" />
</template>
