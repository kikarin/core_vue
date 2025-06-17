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
import { Eye, EyeOff, X } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import * as LucideIcons from 'lucide-vue-next'
import { useForm } from '@inertiajs/vue3'
import { Checkbox } from '@/components/ui/checkbox'
import { Badge } from '@/components/ui/badge'

const props = defineProps<{
  formInputs: {
    name: string
    label: string
    type: 'text' | 'email' | 'password' | 'textarea' | 'select' | 'multi-select' | 'number' | 'radio' | 'icon' | 'checkbox'
    placeholder?: string
    required?: boolean
    help?: string
    options?: { value: string | number; label: string }[]
    showPassword?: { value: boolean }
  }[]
  initialData?: Record<string, any>
}>()

const emit = defineEmits(['save', 'cancel'])

// Inisialisasi form menggunakan useForm dengan data awal
const form = useForm(props.initialData || {})

// State untuk multi-select dropdown
const multiSelectOpen = ref<Record<string, boolean>>({})

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

const handleSubmit = (e: Event) => {
  e.preventDefault()
  emit('save', form)
}

const togglePassword = (field: { value: boolean }) => {
  field.value = !field.value
}

// Multi-select functions
const toggleMultiSelect = (fieldName: string) => {
  multiSelectOpen.value[fieldName] = !multiSelectOpen.value[fieldName]
}

const selectMultiOption = (fieldName: string, value: string | number) => {
  const currentValues = form[fieldName] || []
  if (currentValues.includes(value)) {
    form[fieldName] = currentValues.filter((v: any) => v !== value)
  } else {
    form[fieldName] = [...currentValues, value]
  }
}

const removeMultiOption = (fieldName: string, value: string | number) => {
  const currentValues = form[fieldName] || []
  form[fieldName] = currentValues.filter((v: any) => v !== value)
}

const getSelectedLabels = (fieldName: string, options: { value: string | number; label: string }[]) => {
  const selectedValues = form[fieldName] || []
  return options.filter(option => selectedValues.includes(option.value))
}
</script>

<template>
  <div class="w-full">
    <form @submit="handleSubmit" class="space-y-6">
      <div v-for="input in formInputs" :key="input.name" class="grid grid-cols-12 gap-4 items-start">
        <label class="col-span-3 text-sm font-medium pt-2">{{ input.label }}</label>
        <div class="col-span-9">
          <!-- MULTI-SELECT -->
          <div v-if="input.type === 'multi-select'" class="relative">
            <div @click="toggleMultiSelect(input.name)"
              class="w-full min-h-[40px] rounded-md border border-input bg-background px-3 py-2 text-sm cursor-pointer flex flex-wrap gap-1 items-center"
              :class="{ 'border-ring ring-2 ring-ring ring-offset-2': multiSelectOpen[input.name] }">
              <!-- Selected badges -->
              <div v-if="form[input.name] && form[input.name].length > 0" class="flex flex-wrap gap-1">
                <Badge v-for="selected in getSelectedLabels(input.name, input.options || [])" :key="selected.value"
                  variant="secondary" class="text-xs flex items-center gap-1">
                  {{ selected.label }}
                  <X class="h-3 w-3 cursor-pointer hover:text-destructive"
                    @click.stop="removeMultiOption(input.name, selected.value)" />
                </Badge>
              </div>
              <!-- Placeholder -->
              <div v-else class="text-muted-foreground">
                {{ input.placeholder }}
              </div>
            </div>

            <!-- Dropdown options -->
            <div v-if="multiSelectOpen[input.name]"
              class="absolute top-full left-0 right-0 z-50 mt-1 max-h-60 overflow-auto rounded-md border bg-popover p-1 shadow-lg">
              <div v-for="option in input.options" :key="option.value"
                @click="selectMultiOption(input.name, option.value)"
                class="flex items-center space-x-2 rounded-sm px-2 py-1.5 text-sm cursor-pointer hover:bg-accent hover:text-accent-foreground">
                <Checkbox :model-value="(form[input.name] || []).includes(option.value)" @update:modelValue="checked => {
                  const selected = form[input.name] || []
                  if (checked) {
                    form[input.name] = [...selected, option.value]
                  } else {
                    form[input.name] = selected.filter((v: any) => v !== option.value)
                  }
                }" />
                <span>{{ option.label }}</span>
              </div>
            </div>
          </div>

          <!-- ICON SELECT -->
          <Select v-else-if="input.type === 'icon'" :required="input.required" :model-value="form[input.name]"
            @update:modelValue="val => form[input.name] = val">
            <SelectTrigger class="w-full">
              <SelectValue :placeholder="input.placeholder">
                <template v-if="form[input.name]">
                  <component :is="LucideIcons[form[input.name] as keyof typeof LucideIcons]"
                    class="h-4 w-4 inline-block mr-2" />
                  {{ form[input.name] }}
                </template>
              </SelectValue>
            </SelectTrigger>
            <SelectContent class="max-h-[300px]">
              <div class="grid grid-cols-4 gap-2 p-2">
                <SelectItem v-for="option in iconOptions" :key="option.value" :value="option.value"
                  class="flex items-center gap-2 p-2 hover:bg-accent rounded-md cursor-pointer">
                  <component :is="LucideIcons[option.icon as keyof typeof LucideIcons]" class="h-4 w-4" />
                  <span class="text-sm">{{ option.label }}</span>
                </SelectItem>
              </div>
            </SelectContent>
          </Select>

          <!-- TEXTAREA -->
          <textarea v-else-if="input.type === 'textarea'" v-model="form[input.name]" :placeholder="input.placeholder"
            :required="input.required"
            class="w-full min-h-[100px] rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />

          <!-- SELECT -->
          <Select v-else-if="input.type === 'select'" :required="input.required" :model-value="form[input.name]"
            @update:modelValue="val => form[input.name] = val">
            <SelectTrigger class="w-full">
              <SelectValue :placeholder="input.placeholder" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="option in input.options" :key="option.value" :value="option.value">
                {{ option.label }}
              </SelectItem>
            </SelectContent>
          </Select>

          <!-- RADIO -->
          <div v-else-if="input.type === 'radio'" class="flex gap-4">
            <label v-for="option in input.options" :key="option.value"
              class="inline-flex items-center space-x-2 cursor-pointer">
              <input type="radio" :name="input.name" :value="option.value" v-model="form[input.name]"
                :required="input.required" class="form-radio text-primary border-input focus:ring-ring" />
              <span class="text-sm">{{ option.label }}</span>
            </label>
          </div>

          <!-- CHECKBOX GROUP -->
          <div v-else-if="input.type === 'checkbox' && Array.isArray(input.options)" class="flex flex-col gap-2">
            <div v-for="option in input.options" :key="option.value" class="flex items-center space-x-2">
              <Checkbox :id="`${input.name}-${option.value}`" :value="option.value"
                :checked="Array.isArray(form[input.name]) && form[input.name].includes(option.value)" @update:checked="checked => {
                  const selected = form[input.name] || []
                  if (checked) {
                    form[input.name] = [...selected, option.value]
                  } else {
                    form[input.name] = selected.filter((v: any) => v !== option.value)
                  }
                }" />
              <label :for="`${input.name}-${option.value}`" class="text-sm">
                {{ option.label }}
              </label>
            </div>
          </div>

          <!-- PASSWORD WITH TOGGLE -->
          <div v-else-if="input.type === 'password'" class="relative">
            <Input v-model="form[input.name]" :type="input.showPassword?.value ? 'text' : 'password'"
              :placeholder="input.placeholder" :required="input.required" />
            <Button type="button" variant="ghost" size="sm" class="absolute right-2 top-1/2 -translate-y-1/2"
              @click="togglePassword(input.showPassword!)">
              <Eye v-if="!input.showPassword?.value" class="h-4 w-4" />
              <EyeOff v-else class="h-4 w-4" />
            </Button>
          </div>

          <!-- DEFAULT INPUT (text, email, number) -->
          <Input v-else v-model="form[input.name]" :type="input.type" :placeholder="input.placeholder"
            :required="input.required" />

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

  <!-- Overlay untuk menutup multi-select dropdown -->
  <div v-if="Object.values(multiSelectOpen).some(Boolean)" @click="multiSelectOpen = {}" class="fixed inset-0 z-40">
  </div>
</template>