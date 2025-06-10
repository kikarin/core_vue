<script setup lang="ts">
import { ref, computed } from 'vue'
import { type PropType } from 'vue'
import RowActions from '@/pages/modules/components/tables/RowActions.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import * as LucideIcons from 'lucide-vue-next'

const props = defineProps({
  columns: {
    type: Array as PropType<{
      key: string
      label: string
      className?: string
      searchable?: boolean
      orderable?: boolean
      visible?: boolean
    }[]>,
    required: true,
  },
  rows: {
    type: Array as PropType<any[]>,
    required: true,
  },
  actions: {
    type: Function as PropType<(row: any) => { label: string; onClick: () => void; permission?: string }[]>,
    default: () => [],
  },
  selected: {
    type: Array as PropType<number[]>,
    default: () => [],
  },
  baseUrl: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:selected'])

const searchQuery = ref('')
const pageLength = ref(10)
const currentPage = ref(1)
const sortKey = ref<string | null>(null)
const sortOrder = ref<'asc' | 'desc'>('asc')

const visibleColumns = computed(() => {
  return props.columns.filter(col => col.visible !== false)
})

const filteredRows = computed(() => {
  if (!searchQuery.value) return props.rows
  const query = searchQuery.value.toLowerCase()
  const searchableCols = props.columns.filter(c => c.searchable !== false)

  return props.rows.filter(row =>
    searchableCols.some(col =>
      String(row[col.key] ?? '').toLowerCase().includes(query)
    )
  )
})

const sortedRows = computed(() => {
  const rows = [...filteredRows.value]
  if (!sortKey.value) return rows

  const col = props.columns.find(c => c.key === sortKey.value)
  if (col?.orderable === false) return rows

  const key = sortKey.value
  return rows.sort((a, b) => {
    const aVal = a[key]
    const bVal = b[key]

    if (aVal === bVal) return 0
    if (sortOrder.value === 'asc') return aVal > bVal ? 1 : -1
    return aVal < bVal ? 1 : -1
  })
})

const totalPages = computed(() => Math.ceil(sortedRows.value.length / pageLength.value))

const paginatedRows = computed(() => {
  const start = (currentPage.value - 1) * pageLength.value
  const end = start + pageLength.value
  return sortedRows.value.slice(start, end)
})

const getPageNumbers = () => {
  const pages = []
  const maxPages = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxPages / 2))
  const end = Math.min(totalPages.value, start + maxPages - 1)

  if (end - start + 1 < maxPages) {
    start = Math.max(1, end - maxPages + 1)
  }

  for (let i = start; i <= end; i++) pages.push(i)
  return pages
}

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const sortBy = (key: string) => {
  const col = props.columns.find(c => c.key === key)
  if (col?.orderable === false) return

  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
}

const toggleSelect = (index: number) => {
  if (props.selected.includes(index)) {
    emit('update:selected', props.selected.filter(i => i !== index))
  } else {
    emit('update:selected', [...props.selected, index])
  }
}

const toggleSelectAll = (checked: boolean) => {
  emit('update:selected', checked ? props.rows.map((_, i) => i) : [])
}
</script>

<template>
  <div class="space-y-4">
    <!-- Search and Length -->
    <div class="flex flex-wrap justify-between items-center gap-4">
      <div class="flex items-center gap-2">
        <span class="text-sm text-muted-foreground">Show</span>
        <select v-model="pageLength"
          class="w-24 rounded-md border border-input bg-background px-3 py-1.5 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          <option :value="10">10</option>
          <option :value="25">25</option>
          <option :value="50">50</option>
          <option :value="100">100</option>
          <option :value="500">500</option>
          <option :value="filteredRows.length">All</option>
        </select>
        <span class="text-sm text-muted-foreground">entries</span>
      </div>
      <div class="w-full sm:w-64">
        <Input v-model="searchQuery" placeholder="Search..." class="w-full" />
      </div>
    </div>

    <!-- Table -->
    <div class="border rounded-md">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y text-sm text-left">
          <thead class="bg-muted">
            <tr>
              <th class="w-12 px-3 py-2 text-center">No</th>
              <th class="w-10 px-3 py-2 text-center">
                <input type="checkbox" :checked="props.selected.length === props.rows.length"
                  @change="(e) => toggleSelectAll((e.target as HTMLInputElement).checked)" />
              </th>
              <th class="w-28 px-3 py-2 text-center">Actions</th>
              <th v-for="col in visibleColumns" :key="col.key"
                class="px-4 py-2 text-left cursor-pointer select-none whitespace-nowrap" @click="sortBy(col.key)">
                <div class="flex items-center gap-1">
                  {{ col.label }}
                  <span v-if="sortKey === col.key">
                    <span v-if="sortOrder === 'asc'">▲</span>
                    <span v-else>▼</span>
                  </span>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, index) in paginatedRows" :key="index" class="border-t hover:bg-muted/40 transition">
              <td class="w-12 px-3 py-2 text-center">
                {{ (currentPage - 1) * pageLength + index + 1 }}
              </td>
              <td class="w-10 px-3 py-2 text-center">
                <input type="checkbox" :checked="props.selected.includes(index)" @change="() => toggleSelect(index)" />
              </td>
              <td class="w-28 px-3 py-2 text-center">
                <RowActions v-if="actions(row).length > 0" :actions="actions(row)" :id="row.id" :base-url="baseUrl"
                  :on-delete="() => console.log('Delete', row.id)" />
              </td>
              <td v-for="col in visibleColumns" :key="col.key" class="px-4 py-2 text-left whitespace-nowrap"
                :class="col.className">
                <!-- Render icon component dynamically -->
                <component v-if="col.key === 'icon' && row[col.key] && row[col.key] in LucideIcons"
                  :is="LucideIcons[row[col.key] as keyof typeof LucideIcons]" class="w-4 h-4 text-muted-foreground" />
                <span v-else>
                  {{ row[col.key] }}
                </span>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Info -->
      <div class="flex flex-wrap justify-between items-center gap-2 p-4 border-t text-sm text-muted-foreground">
        <span>
          Showing {{ (currentPage - 1) * pageLength + 1 }}
          to {{ Math.min(currentPage * pageLength, filteredRows.length) }}
          of {{ filteredRows.length }} entries
        </span>

        <div class="flex flex-wrap items-center gap-2">
          <Button size="sm" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)"
            class="bg-muted/40 text-foreground">
            Previous
          </Button>
          <div class="flex flex-wrap items-center gap-1">
            <Button v-for="page in getPageNumbers()" :key="page" size="sm" class="px-3 py-1.5 text-sm border rounded-md"
              :class="[
                currentPage === page
                  ? 'bg-primary text-primary-foreground border-primary'
                  : 'bg-muted text-black dark:text-white border-input'
              ]" @click="goToPage(page)">
              {{ page }}
            </Button>
          </div>
          <Button size="sm" :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)"
            class="bg-muted/40 text-foreground">
            Next
          </Button>
        </div>
      </div>

    </div>
  </div>
</template>
