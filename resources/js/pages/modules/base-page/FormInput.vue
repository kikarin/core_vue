<script setup lang="ts">
import { ref, computed } from 'vue'
import { Input } from '@/components/ui/input'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import ButtonsForm from './ButtonsForm.vue'
import { onMounted } from 'vue'
import { Eye, EyeOff } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import * as LucideIcons from 'lucide-vue-next'

const props = defineProps<{
  formInputs: {
    name: string
    label: string
    type: 'text' | 'email' | 'password' | 'textarea' | 'select' | 'number' | 'radio' | 'icon'
    placeholder?: string
    required?: boolean
    help?: string
    options?: { value: string | number; label: string }[]
    showPassword?: { value: boolean }
  }[]
  initialData?: Record<string, any>
}>()

const formData = ref<Record<string, any>>(props.initialData || {})
const emit = defineEmits(['save', 'cancel'])

// Memisahkan icon options ke computed property
const iconOptions = computed(() => {
  return Object.keys(LucideIcons)
    .filter(key => key !== 'default')
    .map(key => ({
      value: key,
      label: key,
      icon: key
    }))
})

onMounted(() => {
  if (props.initialData) {
    formData.value = { ...props.initialData }
  }
})

const handleSubmit = (e: Event) => {
  e.preventDefault()
  emit('save', formData.value)
}

const togglePassword = (field: { value: boolean }) => {
  field.value = !field.value
}
</script>

<template>
  <div class="w-full">
    <form @submit="handleSubmit" class="space-y-6">
      <div v-for="input in formInputs" :key="input.name" class="grid grid-cols-12 gap-4 items-center">
        <label class="col-span-3 text-sm font-medium">{{ input.label }}</label>
        <div class="col-span-9">
          <!-- ICON SELECT -->
          <Select
            v-if="input.type === 'icon'"
            :required="input.required"
            :model-value="formData[input.name]"
            @update:modelValue="val => formData[input.name] = val"
          >
            <SelectTrigger class="w-full">
              <SelectValue :placeholder="input.placeholder">
                <template v-if="formData[input.name]">
                  <component 
                    :is="LucideIcons[formData[input.name] as keyof typeof LucideIcons]" 
                    class="h-4 w-4 inline-block mr-2"
                  />
                  {{ formData[input.name] }}
                </template>
              </SelectValue>
            </SelectTrigger>
            <SelectContent class="max-h-[300px]">
              <div class="grid grid-cols-4 gap-2 p-2">
                <SelectItem
                  v-for="option in iconOptions"
                  :key="option.value"
                  :value="option.value"
                  class="flex items-center gap-2 p-2 hover:bg-accent rounded-md cursor-pointer"
                >
                  <component 
                    :is="LucideIcons[option.icon as keyof typeof LucideIcons]" 
                    class="h-4 w-4"
                  />
                  <span class="text-sm">{{ option.label }}</span>
                </SelectItem>
              </div>
            </SelectContent>
          </Select>

          <!-- TEXTAREA -->
          <textarea
            v-else-if="input.type === 'textarea'"
            v-model="formData[input.name]"
            :placeholder="input.placeholder"
            :required="input.required"
            class="w-full min-h-[100px] rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
          />

          <!-- SELECT -->
          <Select
            v-else-if="input.type === 'select'"
            :required="input.required"
            :model-value="formData[input.name]"
            @update:modelValue="val => formData[input.name] = val"
          >
            <SelectTrigger class="w-full">
              <SelectValue :placeholder="input.placeholder" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="option in input.options"
                :key="option.value"
                :value="option.value"
              >
                {{ option.label }}
              </SelectItem>
            </SelectContent>
          </Select>

          <!-- RADIO -->
          <div v-else-if="input.type === 'radio'" class="flex gap-4">
            <label
              v-for="option in input.options"
              :key="option.value"
              class="inline-flex items-center space-x-2 cursor-pointer"
            >
              <input
                type="radio"
                :name="input.name"
                :value="option.value"
                v-model="formData[input.name]"
                :required="input.required"
                class="form-radio text-primary border-input focus:ring-ring"
              />
              <span class="text-sm">{{ option.label }}</span>
            </label>
          </div>

          <!-- PASSWORD WITH TOGGLE -->
          <div v-else-if="input.type === 'password'" class="relative">
            <Input
              v-model="formData[input.name]"
              :type="input.showPassword?.value ? 'text' : 'password'"
              :placeholder="input.placeholder"
              :required="input.required"
            />
            <Button
              type="button"
              variant="ghost"
              size="sm"
              class="absolute right-2 top-1/2 -translate-y-1/2"
              @click="togglePassword(input.showPassword!)"
            >
              <Eye v-if="!input.showPassword?.value" class="h-4 w-4" />
              <EyeOff v-else class="h-4 w-4" />
            </Button>
          </div>

          <!-- DEFAULT INPUT (text, email, number) -->
          <Input
            v-else
            v-model="formData[input.name]"
            :type="input.type"
            :placeholder="input.placeholder"
            :required="input.required"
          />
          
          <!-- Help text -->
          <p v-if="input.help" class="mt-1 text-sm text-muted-foreground">
            {{ input.help }}
          </p>
        </div>
      </div>

      <!-- BUTTONS -->
      <div class="grid grid-cols-12 items-center">
        <div class="col-span-3"></div>
        <div class="col-span-9">
          <ButtonsForm @save="handleSubmit" @cancel="emit('cancel')" />
        </div>
      </div>
    </form>
  </div>
</template>
