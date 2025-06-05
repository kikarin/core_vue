<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { MoreHorizontal } from 'lucide-vue-next'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  id: string | number
  baseUrl: string
  actions?: { label: string; onClick: () => void; permission?: string }[]
  show?: boolean
  edit?: boolean
  delete?: boolean
  onDelete?: () => void
}>()

const showMenu = ref(false)
const menuRef = ref<HTMLElement | null>(null)

const closeMenu = () => {
  showMenu.value = false
}

const items = computed(() => {
  if (props.actions && props.actions.length > 0) {
    return props.actions.map(action => ({
      label: action.label,
      action: action.onClick,
    }))
  }

  const links: { label: string; action: () => void }[] = []

  if (props.show !== false) {
    links.push({
      label: 'Detail',
      action: () => router.visit(`${props.baseUrl}/${props.id}`),
    })
  }

  if (props.edit !== false) {
    links.push({
      label: 'Edit',
      action: () => router.visit(`${props.baseUrl}/${props.id}/edit`),
    })
  }

  if (props.delete !== false && props.onDelete) {
    links.push({
      label: 'Delete',
      action: () => props.onDelete?.(),
    })
  }

  return links
})

const handleClickOutside = (event: MouseEvent) => {
  if (showMenu.value && menuRef.value && !menuRef.value.contains(event.target as Node)) {
    closeMenu()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <div class="relative inline-block text-left" ref="menuRef">
    <Button variant="ghost" size="icon" @click.stop="showMenu = !showMenu">
      <MoreHorizontal class="w-4 h-4" />
    </Button>

    <div
      v-if="showMenu"
      class="absolute right-0 z-10 mt-2 w-36 rounded-md border border-border bg-popover shadow-lg dark:border-border dark:bg-popover"
    >
      <button
        v-for="item in items"
        :key="item.label"
        @click="() => { item.action(); closeMenu() }"
        class="block w-full text-left px-3 py-2 hover:bg-muted text-sm text-foreground"
      >
        {{ item.label }}
      </button>
    </div>
  </div>
</template>
