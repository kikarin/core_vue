<script setup lang="ts">
import { Input } from '@/components/ui/input'

const props = defineProps<{
  modelValue: string | number
  placeholder?: string
  required?: boolean
  type?: 'text' | 'email' | 'number'
}>()

const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <Input
    :type="type ?? 'text'"
    :placeholder="placeholder"
    :required="required"
    :value="modelValue"
    @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
  />
</template>
