<script setup lang="ts">
import { ref } from 'vue'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import ButtonsForm from './ButtonsForm.vue'

defineProps<{
  formInputs: {
    name: string
    label: string
    type: 'text' | 'email' | 'password' | 'textarea' | 'select' | 'number'
    placeholder?: string
    required?: boolean
    options?: { value: string | number; label: string }[]
  }[]
}>()


const formData = ref<Record<string, any>>({})

const emit = defineEmits(['save', 'cancel'])

const submit = () => {
  emit('save', formData.value)
}
</script>

<template>
  <div class="w-full">
    <form @submit.prevent="submit" class="space-y-6">
      <div v-for="input in formInputs" :key="input.name" class="grid grid-cols-12 gap-4 items-center">
        <label class="col-span-3 text-sm font-medium">{{ input.label }}</label>
        <div class="col-span-9">
          <textarea v-if="input.type === 'textarea'" v-model="formData[input.name]" :placeholder="input.placeholder"
            :required="input.required"
            class="w-full min-h-[100px] rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
          <Select v-else-if="input.type === 'select'" v-model="formData[input.name]" :required="input.required">
            <SelectTrigger class="w-full">
              <SelectValue :placeholder="input.placeholder" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="option in input.options" :key="option.value" :value="option.value">
                {{ option.label }}
              </SelectItem>
            </SelectContent>
          </Select>
          <Input v-else v-model="formData[input.name]" :type="input.type" :placeholder="input.placeholder"
            :required="input.required" />
        </div>
      </div>
      <div class="grid grid-cols-12 items-center">
        <div class="col-span-3"></div>
        <div class="col-span-9">
          <ButtonsForm @save="submit" @cancel="emit('cancel')" />
        </div>
      </div>
    </form>
  </div>
</template>