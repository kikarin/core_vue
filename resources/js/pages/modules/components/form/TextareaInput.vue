<script setup lang="ts">
defineProps<{
  modelValue: string
  placeholder?: string
  required?: boolean
}>()
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <textarea
    class="w-full min-h-[100px] rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
    :placeholder="placeholder"
    :required="required"
    :value="modelValue"
    @input="emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
  />
</template>
