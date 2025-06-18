<!-- components/DataTable.vue -->
<script setup lang="ts">
import {
  Table, TableBody,  TableCell, 
  TableHead, TableHeader, TableRow,
} from '@/components/ui/table'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import RowActions from '@/pages/modules/components/tables/RowActions.vue'
import * as LucideIcons from 'lucide-vue-next'

import type { Column, Sort } from './datatable/types'
import { useDataTable } from './datatable/useDataTable'
import { type PropType } from 'vue'

const props = defineProps({
  columns: { type: Array as PropType<Column[]>, required: true },
  rows: { type: Array as PropType<any[]>, required: true },
  actions: { type: Function as PropType<(row: any) => { label: string; onClick: () => void; permission?: string }[]>, default: () => [] },
  selected: { type: Array as PropType<number[]>, default: () => [] },
  baseUrl: { type: String, default: '' },
  total: { type: Number, required: true },
  search: { type: String, default: '' },
  sort: { type: Object as PropType<Sort>, default: () => ({ key: '', order: 'asc' }) },
  page: { type: Number, default: 1 },
  perPage: { type: Number, default: 10 },
})

const emit = defineEmits([
  'update:selected', 'update:search', 'update:sort',
  'update:page', 'update:perPage', 'deleted'
])

const {
  visibleColumns, totalPages, getPageNumbers,
  sortBy, toggleSelect, toggleSelectAll
} = useDataTable(props, emit)
</script>

<template>
    <div class="space-y-4">
        <!-- Search and Length -->
        <div
            class="flex flex-col flex-wrap items-center justify-center gap-4 text-center sm:flex-row sm:justify-between">
            <div class="flex items-center gap-2">
                <span class="text-muted-foreground text-sm">Show</span>
                <Select :model-value="props.perPage" @update:model-value="(val) => emit('update:perPage', val === 'all' ? -1 : Number(val))">
                    <SelectTrigger class="w-24">
                        <SelectValue placeholder="Select" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem :value="10">10</SelectItem>
                        <SelectItem :value="25">25</SelectItem>
                        <SelectItem :value="50">50</SelectItem>
                        <SelectItem :value="100">100</SelectItem>
                        <SelectItem :value="500">500</SelectItem>
                        <SelectItem value="all">All</SelectItem>
                    </SelectContent>
                </Select>

                <span class="text-muted-foreground text-sm">entries</span>
            </div>
            <div class="w-full sm:w-64">
                <Input
                    :model-value="props.search"
                    @update:model-value="(val) => emit('update:search', val)"
                    placeholder="Search..."
                    class="w-full"
                />
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-md border">
            <div class="overflow-x-auto">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-12 text-center">No</TableHead>
                            <TableHead class="w-10 text-center">
                                <label
                                    class="inline-flex items-center justify-center w-5 h-5 rounded border border-input bg-background cursor-pointer relative">
                                    <input type="checkbox" class="sr-only peer"
                                        :checked="props.selected.length > 0 && props.selected.length === props.rows.length"
                                        @change="(e) => toggleSelectAll((e.target as HTMLInputElement).checked)" />
                                    <div
                                        class="h-3 w-3 scale-0 transform rounded-sm bg-primary transition-all peer-checked:scale-100">
                                    </div>
                                </label>
                            </TableHead>
                            <TableHead class="w-28 text-center">Actions</TableHead>
                            <TableHead v-for="col in visibleColumns" :key="col.key" class="cursor-pointer select-none"
                                @click="col.sortable === false ? null : sortBy(col.key)">
                                <div class="flex items-center gap-1">
                                    {{ col.label }}
                                    <span v-if="props.sort.key === col.key">
                                        <span v-if="props.sort.order === 'asc'">▲</span>
                                        <span v-else>▼</span>
                                    </span>
                                </div>
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(row, index) in props.rows" :key="index"
                            class="hover:bg-muted/40 border-t transition">
                            <TableCell class="text-center">
                                {{ (props.page - 1) * props.perPage + index + 1 }}
                            </TableCell>
                            <TableCell class="text-center">
                                <label
                                    class="inline-flex items-center justify-center w-5 h-5 rounded border border-input bg-background cursor-pointer relative">
                                    <input type="checkbox" class="sr-only peer"
                                        :checked="props.selected.includes(row.id)"
                                        @change="() => toggleSelect(row.id)" />
                                    <svg class="h-4 w-4 text-primary opacity-0 scale-75 transition-all duration-200 peer-checked:opacity-100 peer-checked:scale-100"
                                        fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </label>
                            </TableCell>
                            <TableCell class="text-center">
                                <RowActions v-if="actions(row).length > 0" :actions="actions(row)" :id="row.id"
                                    :base-url="baseUrl" :on-delete="() => emit('deleted')" />
                            </TableCell>
                            <TableCell v-for="col in visibleColumns" :key="col.key"
                                :class="typeof col.className === 'function' ? col.className(row) : col.className">
                                <component v-if="col.key === 'icon' && row[col.key] && row[col.key] in LucideIcons"
                                    :is="LucideIcons[row[col.key] as keyof typeof LucideIcons]"
                                    class="text-muted-foreground h-4 w-4" />
                                <span v-else>
                                    <span v-if="typeof col.format === 'function'" v-html="col.format(row)"></span>
                                    <span v-else>{{ row[col.key] }}</span>
                                </span>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- Pagination Info -->
            <div
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 border-t p-4 text-center text-sm md:flex-row md:justify-between">
                <span>
                    Showing {{ (props.page - 1) * props.perPage + 1 }}
                    to {{ Math.min(props.page * props.perPage, props.total) }}
                    of {{ props.total }} entries
                </span>

                <div class="flex flex-wrap items-center justify-center gap-2">
                    <Button size="sm" :disabled="props.page === 1" @click="emit('update:page', props.page - 1)"
                        class="bg-muted/40 text-foreground">
                        Previous
                    </Button>
                    <div class="flex flex-wrap items-center gap-1">
                        <Button v-for="page in getPageNumbers()" :key="page" size="sm"
                            class="rounded-md border px-3 py-1.5 text-sm" :class="[
                                props.page === page
                                    ? 'bg-primary text-primary-foreground border-primary'
                                    : 'bg-muted border-input text-black dark:text-white',
                            ]" @click="emit('update:page', page)">
                            {{ page }}
                        </Button>
                    </div>
                    <Button size="sm" :disabled="props.page === totalPages" @click="emit('update:page', props.page + 1)"
                        class="bg-muted/40 text-foreground">
                        Next
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>