<script setup lang="ts">
const props = defineProps<{
  groups: {
    label: string
    children: { id: number; label: string }[]
  }[]
  modelValue: number[]
}>()

const emit = defineEmits(['update:modelValue'])

const isChecked = (id: number) => props.modelValue.includes(id)

const toggle = (id: number) => {
  const updated = isChecked(id)
    ? props.modelValue.filter(i => i !== id)
    : [...props.modelValue, id]
  emit('update:modelValue', updated)
}

// Optional: Toggle entire group
const isGroupChecked = (group: typeof props.groups[0]) =>
  group.children.every(perm => isChecked(perm.id))

const toggleGroup = (group: typeof props.groups[0]) => {
  const allIds = group.children.map(p => p.id)
  const updated = isGroupChecked(group)
    ? props.modelValue.filter(id => !allIds.includes(id))
    : [...new Set([...props.modelValue, ...allIds])]
  emit('update:modelValue', updated)
}
</script>

<template>
  <div class="space-y-4">
    <div
      v-for="group in groups"
      :key="group.label"
      class="border rounded-md p-4 bg-card"
    >
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-sm text-muted-foreground">
          {{ group.label }}
        </h3>
        <label class="inline-flex items-center space-x-2 text-sm cursor-pointer">
          <input
            type="checkbox"
            :checked="isGroupChecked(group)"
            @change="() => toggleGroup(group)"
          />
          <span>Select All</span>
        </label>
      </div>

      <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2">
        <label
          v-for="perm in group.children"
          :key="perm.id"
          class="flex items-center space-x-2 text-sm cursor-pointer"
        >
          <input
            type="checkbox"
            :checked="isChecked(perm.id)"
            @change="() => toggle(perm.id)"
          />
          <span>{{ perm.label }}</span>
        </label>
      </div>
    </div>
  </div>
</template>
