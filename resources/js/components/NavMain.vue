`<script setup lang="ts">
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { ChevronDown } from 'lucide-vue-next'
import { type NavItem } from '@/types'

defineProps<{
  items: NavItem[]
  sectionTitle?: string
}>()

const openGroups = ref<string[]>([])
const page = usePage()

const toggleGroup = (title: string) => {
  if (openGroups.value.includes(title)) {
    openGroups.value = openGroups.value.filter(t => t !== title)
  } else {
    openGroups.value.push(title)
  }
}

const isActive = (href?: string) => {
  if (!href) return false
  return page.url.startsWith(href)
}
</script>

<template>
  <div class="px-2 py-2 border-t border-border">
    <p
      v-if="sectionTitle"
      class="text-xs uppercase text-muted-foreground font-semibold px-2 mb-2 tracking-wide"
    >
      {{ sectionTitle }}
    </p>

    <ul class="space-y-1">
      <template v-for="item in items" :key="item.title">
        <!-- Dropdown -->
        <li v-if="item.children">
          <button
            @click="toggleGroup(item.title)"
            class="flex items-center justify-between w-full px-3 py-2 rounded-md hover:bg-muted text-sm font-medium transition"
          >
            <span class="flex items-center gap-2">
              <component :is="item.icon" class="w-4 h-4" />
              {{ item.title }}
            </span>
            <ChevronDown
              class="w-4 h-4 transition-transform"
              :class="{ 'rotate-180': openGroups.includes(item.title) }"
            />
          </button>

          <ul
            v-show="openGroups.includes(item.title)"
            class="ml-4 mt-1 space-y-1 border-l border-border pl-2"
          >
            <template v-for="child in item.children" :key="child.title">
              <li v-if="child.children">
                <button
                  @click="toggleGroup(child.title)"
                  class="flex justify-between w-full px-3 py-1 text-sm rounded hover:bg-muted"
                >
                  {{ child.title }}
                  <ChevronDown
                    class="w-4 h-4 transition-transform"
                    :class="{ 'rotate-180': openGroups.includes(child.title) }"
                  />
                </button>
                <ul
                  v-show="openGroups.includes(child.title)"
                  class="ml-3 mt-1 space-y-1 border-l border-border/50 pl-2"
                >
                  <li v-for="sub in child.children" :key="sub.title">
                    <Link
                      :href="sub.href"
                      class="block px-3 py-1 text-xs rounded hover:bg-muted"
                      :class="{ 'bg-muted font-semibold': isActive(sub.href) }"
                    >
                      {{ sub.title }}
                    </Link>
                  </li>
                </ul>
              </li>

              <li v-else>
                <Link
                  :href="child.href"
                  class="block px-3 py-1 text-sm rounded hover:bg-muted"
                  :class="{ 'bg-muted font-semibold': isActive(child.href) }"
                >
                  {{ child.title }}
                </Link>
              </li>
            </template>
          </ul>
        </li>

        <!-- Normal link -->
        <li v-else>
          <Link
            :href="item.href"
            class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-muted text-sm font-medium transition"
            :class="{ 'bg-muted font-semibold': isActive(item.href) }"
          >
            <component :is="item.icon" class="w-4 h-4" />
            {{ item.title }}
          </Link>
        </li>
      </template>
    </ul>
  </div>
</template>
`