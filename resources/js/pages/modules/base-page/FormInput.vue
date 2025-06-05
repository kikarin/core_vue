<script setup lang="ts">
import { ref } from 'vue'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import ButtonsForm from './ButtonsForm.vue'

defineProps<{
  formInputs: {
    name: string
    label: string
    type: 'text' | 'email' | 'password' | 'textarea' | 'select'
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
  <div class="grid grid-cols-12">
    <div class="col-span-6">
      <div class="bg-card rounded-lg border shadow-sm">
        <form @submit.prevent="submit" class="p-6 space-y-4">
          <div v-for="input in formInputs" :key="input.name" class="space-y-2">
            <label class="block text-sm font-medium">{{ input.label }}</label>
            <div class="relative">
              <textarea v-if="input.type === 'textarea'" v-model="formData[input.name]" :placeholder="input.placeholder"
                :required="input.required"
                class="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
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
          <ButtonsForm @save="submit" @cancel="emit('cancel')" />
        </form>
      </div>
    </div>
  </div>
</template>
