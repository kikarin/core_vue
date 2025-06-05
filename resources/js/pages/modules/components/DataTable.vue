<script setup lang="ts">
import { ref, computed } from 'vue'
import { type PropType } from 'vue'
import RowActions from '@/pages/modules/components/tables/RowActions.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

const props = defineProps({
  columns: {
    type: Array as PropType<{ key: string; label: string }[]>,
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

const filteredRows = computed(() => {
  if (!searchQuery.value) return props.rows

  const query = searchQuery.value.toLowerCase()
  return props.rows.filter(row =>
    Object.values(row).some(value =>
      String(value).toLowerCase().includes(query)
    )
  )
})

const totalPages = computed(() => {
  return Math.ceil(filteredRows.value.length / pageLength.value)
})

const paginatedRows = computed(() => {
  const start = (currentPage.value - 1) * pageLength.value
  const end = start + pageLength.value
  return filteredRows.value.slice(start, end)
})

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

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const getPageNumbers = () => {
  const pages = []
  const maxPages = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxPages / 2))
  let end = Math.min(totalPages.value, start + maxPages - 1)

  if (end - start + 1 < maxPages) {
    start = Math.max(1, end - maxPages + 1)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
}
</script>

<template>
  <div class="space-y-4">
    <!-- Search and Length -->
    <div class="flex justify-between items-center gap-4">
      <div class="w-64">
        <Input v-model="searchQuery" placeholder="Search..." class="w-full" />
      </div>
      <div class="flex items-center gap-2">
        <span class="text-sm text-muted-foreground">Show</span>
        <select v-model="pageLength"
          class="w-20 rounded-md border border-input bg-background px-3 py-1.5 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          <option :value="10">10</option>
          <option :value="25">25</option>
          <option :value="50">50</option>
          <option :value="100">100</option>
        </select>
        <span class="text-sm text-muted-foreground">entries</span>
      </div>
    </div>

    <!-- Table -->
    <div class="border rounded-md overflow-x-auto">
      <table class="min-w-full divide-y text-sm text-left">
        <thead class="bg-muted">
          <tr>
            <th class="px-4 py-2">
              <input type="checkbox" :checked="props.selected.length === rows.length"
                @change="(e) => toggleSelectAll((e.target as HTMLInputElement).checked)" />
            </th>
            <th v-for="col in columns" :key="col.key" class="px-4 py-2">
              {{ col.label }}
            </th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, index) in paginatedRows" :key="index" class="border-t hover:bg-muted/40 transition">
            <td class="px-4 py-2">
              <input type="checkbox" :checked="props.selected.includes(index)" @change="() => toggleSelect(index)" />
            </td>

            <td v-for="col in columns" :key="col.key" class="px-4 py-2">
              {{ row[col.key] }}
            </td>

            <td class="px-4 py-2 text-right">
              <RowActions v-if="actions(row).length > 0" :actions="actions(row)" :id="row.id" :base-url="baseUrl"
                :on-delete="() => console.log('Delete', row.id)" />
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination Info -->
      <div class="flex justify-between items-center p-4 border-t text-sm text-muted-foreground">
        <span>Showing {{ (currentPage - 1) * pageLength + 1 }} to {{ Math.min(currentPage * pageLength,
          filteredRows.length) }} of {{ filteredRows.length }} entries</span>
        <div class="flex items-center gap-2">
          <Button size="sm" variant="outline" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)"
            class="bg-muted/40 hover:bg-muted text-foreground">
            Previous
          </Button>

          <div class="flex items-center gap-1">
            <Button v-for="page in getPageNumbers()" :key="page" size="sm" variant="outline" :class="[
              currentPage === page
                ? 'bg-primary text-primary-foreground hover:bg-primary/90 border-primary'
                : 'bg-muted/40 hover:bg-muted border-input text-gray-800 dark:text-white'
            ]" @click="goToPage(page)">
              {{ page }}
            </Button>
          </div>

          <Button size="sm" variant="outline" :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)"
            class="bg-muted/40 hover:bg-muted text-foreground">
            Next
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>
