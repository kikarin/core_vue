<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import RowActions from '@/pages/modules/components/tables/RowActions.vue';
import * as LucideIcons from 'lucide-vue-next';
import { computed, ref, type PropType } from 'vue';


const props = defineProps({
    columns: {
        type: Array as PropType<
            {
                key: string;
                label: string;
                className?: string;
                searchable?: boolean;
                orderable?: boolean;
                visible?: boolean;
                format?: (row: any) => any;
            }[]
        >,
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
});

const emit = defineEmits(['update:selected']);

const searchQuery = ref('');
const pageLength = ref(10);
const currentPage = ref(1);
const sortKey = ref<string | null>(null);
const sortOrder = ref<'asc' | 'desc'>('asc');

const visibleColumns = computed(() => {
    return props.columns.filter((col) => col.visible !== false);
});

const filteredRows = computed(() => {
    if (!searchQuery.value) return props.rows;
    const query = searchQuery.value.toLowerCase();
    const searchableCols = props.columns.filter((c) => c.searchable !== false);

    return props.rows.filter((row) =>
        searchableCols.some((col) =>
            String(row[col.key] ?? '')
                .toLowerCase()
                .includes(query),
        ),
    );
});

const sortedRows = computed(() => {
    const rows = [...filteredRows.value];
    if (!sortKey.value) return rows;

    const col = props.columns.find((c) => c.key === sortKey.value);
    if (col?.orderable === false) return rows;

    const key = sortKey.value;
    return rows.sort((a, b) => {
        const aVal = a[key];
        const bVal = b[key];

        if (aVal === bVal) return 0;
        if (sortOrder.value === 'asc') return aVal > bVal ? 1 : -1;
        return aVal < bVal ? 1 : -1;
    });
});

const totalPages = computed(() => Math.ceil(sortedRows.value.length / pageLength.value));

const paginatedRows = computed(() => {
    const start = (currentPage.value - 1) * pageLength.value;
    const end = start + pageLength.value;
    return sortedRows.value.slice(start, end);
});

const getPageNumbers = () => {
    const pages = [];
    const maxPages = 5;
    let start = Math.max(1, currentPage.value - Math.floor(maxPages / 2));
    const end = Math.min(totalPages.value, start + maxPages - 1);

    if (end - start + 1 < maxPages) {
        start = Math.max(1, end - maxPages + 1);
    }

    for (let i = start; i <= end; i++) pages.push(i);
    return pages;
};

const goToPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const sortBy = (key: string) => {
    const col = props.columns.find((c) => c.key === key);
    if (col?.orderable === false) return;

    if (sortKey.value === key) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = key;
        sortOrder.value = 'asc';
    }
};

const toggleSelect = (index: number) => {
    if (props.selected.includes(index)) {
        emit(
            'update:selected',
            props.selected.filter((i) => i !== index),
        );
    } else {
        emit('update:selected', [...props.selected, index]);
    }
};

const toggleSelectAll = (checked: boolean) => {
    emit('update:selected', checked ? props.rows.map((_, i) => i) : []);
};
</script>

<template>
    <div class="space-y-4">
        <!-- Search and Length -->
        <div
            class="flex flex-col flex-wrap items-center justify-center gap-4 text-center sm:flex-row sm:justify-between">
            <div class="flex items-center gap-2">
                <span class="text-muted-foreground text-sm">Show</span>
                <Select v-model="pageLength">
                    <SelectTrigger class="w-24">
                        <SelectValue placeholder="Select" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem :value="10">10</SelectItem>
                        <SelectItem :value="25">25</SelectItem>
                        <SelectItem :value="50">50</SelectItem>
                        <SelectItem :value="100">100</SelectItem>
                        <SelectItem :value="500">500</SelectItem>
                        <SelectItem :value="filteredRows.length">All</SelectItem>
                    </SelectContent>
                </Select>

                <span class="text-muted-foreground text-sm">entries</span>
            </div>
            <div class="w-full sm:w-64">
                <Input v-model="searchQuery" placeholder="Search..." class="w-full" />
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
                                        :checked="props.selected.length === props.rows.length"
                                        @change="(e) => toggleSelectAll((e.target as HTMLInputElement).checked)" />
                                    <div
                                        class="h-3 w-3 scale-0 transform rounded-sm bg-primary transition-all peer-checked:scale-100">
                                    </div>
                                </label>
                            </TableHead>
                            <TableHead class="w-28 text-center">Actions</TableHead>
                            <TableHead v-for="col in visibleColumns" :key="col.key" class="cursor-pointer select-none"
                                @click="sortBy(col.key)">
                                <div class="flex items-center gap-1">
                                    {{ col.label }}
                                    <span v-if="sortKey === col.key">
                                        <span v-if="sortOrder === 'asc'">▲</span>
                                        <span v-else>▼</span>
                                    </span>
                                </div>
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(row, index) in paginatedRows" :key="index"
                            class="hover:bg-muted/40 border-t transition">
                            <TableCell class="text-center">
                                {{ (currentPage - 1) * pageLength + index + 1 }}
                            </TableCell>
                            <TableCell class="text-center">
                                <label
                                    class="inline-flex items-center justify-center w-5 h-5 rounded border border-input bg-background cursor-pointer relative">
                                    <input type="checkbox" class="sr-only peer"
                                        :checked="props.selected.includes(index)" @change="() => toggleSelect(index)" />
                                    <svg class="h-4 w-4 text-primary opacity-0 scale-75 transition-all duration-200 peer-checked:opacity-100 peer-checked:scale-100"
                                        fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </label>
                            </TableCell>
                            <TableCell class="text-center">
                                <RowActions v-if="actions(row).length > 0" :actions="actions(row)" :id="row.id"
                                    :base-url="baseUrl" :on-delete="() => console.log('Delete', row.id)" />
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
                    Showing {{ (currentPage - 1) * pageLength + 1 }} to {{ Math.min(currentPage * pageLength,
                        filteredRows.length) }} of
                    {{ filteredRows.length }} entries
                </span>

                <div class="flex flex-wrap items-center justify-center gap-2">
                    <Button size="sm" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)"
                        class="bg-muted/40 text-foreground">
                        Previous
                    </Button>
                    <div class="flex flex-wrap items-center gap-1">
                        <Button v-for="page in getPageNumbers()" :key="page" size="sm"
                            class="rounded-md border px-3 py-1.5 text-sm" :class="[
                                currentPage === page
                                    ? 'bg-primary text-primary-foreground border-primary'
                                    : 'bg-muted border-input text-black dark:text-white',
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
