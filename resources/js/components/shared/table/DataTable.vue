<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { valueUpdater } from '@/lib/utils';
import type { ColumnDef, SortingState } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
import { ChevronDown } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';
import { Pagination } from '../pagination';
import type { PaginateData } from '../pagination/types';
import { Select } from '../select';
import type { SelectOption } from '../select/types';
import type { Filter, SearchConfig, VisibilityState } from './types';

const props = withDefaults(
    defineProps<{
        columns: ColumnDef<TData, TValue>[];
        paginateData: PaginateData<TData[]>;
        columnVisibility?: VisibilityState;
        filter: Filter;
        entryOptions?: SelectOption[];
        searchConfig?: SearchConfig;
    }>(),
    {
        columnVisibility: () => ({}),
    },
);

const sorting = ref<SortingState>([]);
const columnVisibility = ref<VisibilityState>(props.columnVisibility);

const filter = reactive(props.filter);

const emits = defineEmits<{
    filterChange: [value: Filter];
}>();

const table = useVueTable({
    get data() {
        return props.paginateData.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
    },
});

const searchPlaceholder = computed(() => props.searchConfig?.placeholder || 'Search...');

const entryHandler = () => {
    searchHandler();
};

const searchHandler = () => {
    emits('filterChange', filter);
};

const resetHandler = () => {
    filter.search = '';
    searchHandler();
};
</script>

<template>
    <div class="space-y-3">
        <div class="flex flex-col justify-between gap-2 md:flex-row md:items-center">
            <div>
                <form class="flex flex-col gap-2 md:flex-row md:items-center" @submit.prevent="searchHandler">
                    <div>
                        <Input class="min-w-60" :placeholder="searchPlaceholder" v-model="filter.search" />
                    </div>
                    <div class="flex gap-2">
                        <Button class="flex-1 cursor-pointer md:flex-auto" type="submit">Search</Button>
                        <Button class="flex-1 cursor-pointer md:flex-auto" type="reset" variant="secondary" @click="resetHandler">Reset</Button>
                    </div>
                </form>
            </div>
            <div class="flex flex-wrap items-center justify-end gap-2">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="cursor-pointer">
                            Columns
                            <ChevronDown class="ml-2 h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuCheckboxItem
                            v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
                            :key="column.id"
                            class="capitalize"
                            :modelValue="column.getIsVisible()"
                            @update:modelValue="
                                (value) => {
                                    column.toggleVisibility(!!value);
                                }
                            "
                        >
                            {{ column.id.split('_').join(' ') }}
                        </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell :colspan="columns.length" class="h-24 text-center"> No results. </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>
        <div class="flex flex-col items-center justify-end gap-2 space-x-2 py-2 md:flex-row">
            <div class="flex-1">
                <Select
                    v-if="entryOptions"
                    :options="entryOptions"
                    placeholder="Select Entries"
                    v-model="filter.entries"
                    @update:model-value="entryHandler"
                />
            </div>
            <div class="space-x-2">
                <Pagination :paginate-data="paginateData" />
            </div>
        </div>
    </div>
</template>
