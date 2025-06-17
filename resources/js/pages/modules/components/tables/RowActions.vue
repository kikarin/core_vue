<script setup lang="ts">
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { MoreHorizontal } from 'lucide-vue-next'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps<{
  id: string | number
  baseUrl: string
  actions?: { label: string; onClick: () => void; permission?: string }[]
  show?: boolean
  edit?: boolean
  delete?: boolean
  onDelete?: () => void
}>()

const items = computed(() => {
if (props.actions && props.actions.length > 0) {
  return props.actions.map(action => ({
    label: action.label,
    action: action.onClick,
    icon: undefined,
  }))
}

const links: { label: string; action: () => void; icon?: any }[] = []

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
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" size="icon">
        <MoreHorizontal class="w-4 h-4" />
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent class="w-40">
      <DropdownMenuItem v-for="item in items" :key="item.label" @click="item.action" class="flex items-center gap-2">
        <component :is="item.icon" class="w-4 h-4" v-if="item.icon" />
        {{ item.label }}
      </DropdownMenuItem>

    </DropdownMenuContent>
  </DropdownMenu>
</template>
