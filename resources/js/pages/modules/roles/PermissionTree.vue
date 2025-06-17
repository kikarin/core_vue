<script setup lang="ts">
const props = defineProps<{
  groups: {
    label: string
    children: { id: number; label: string }[]
  }[]
  modelValue: number[]
}>()

console.log('PermissionTree.vue groups:', props.groups)
console.log('PermissionTree.vue modelValue:', props.modelValue)

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
  group.children.length > 0 && group.children.every(perm => isChecked(perm.id))

const toggleGroup = (group: typeof props.groups[0]) => {
  const allIds = group.children.map(p => p.id)
  const updated = isGroupChecked(group)
    ? props.modelValue.filter(id => !allIds.includes(id))
    : [...new Set([...props.modelValue, ...allIds])]
  emit('update:modelValue', updated)
}
</script>

<template>
  <div v-if="groups.length" class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div
      v-for="group in groups"
      :key="group.label"
      class="rounded-lg border bg-card shadow-sm"
    >
      <!-- Card Header -->
      <div class="flex items-center justify-between border-b px-4 py-3">
        <h3 class="text-sm font-semibold tracking-tight">{{ group.label }}</h3>
        <input
          type="checkbox"
          class="form-checkbox scale-125 accent-primary"
          :checked="isGroupChecked(group)"
          @change="() => toggleGroup(group)"
          :disabled="!group.children.length"
        />
      </div>
      <!-- Permissions List -->
      <ul class="divide-y">
        <li
          v-for="perm in group.children"
          :key="perm.id"
          class="px-4 py-2 flex items-center gap-2 text-sm"
        >
          <input
            type="checkbox"
            class="form-checkbox scale-110 accent-primary"
            :checked="isChecked(perm.id)"
            @change="() => toggle(perm.id)"
          />
          <span>{{ perm.label }}</span>
        </li>
        <li v-if="!group.children.length" class="px-4 py-2 text-muted-foreground text-xs">Tidak ada permission</li>
      </ul>
    </div>
  </div>
  <div v-else class="text-center text-muted-foreground py-8">Tidak ada permission group tersedia.</div>
</template>
