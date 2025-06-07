<script setup lang="ts" generic="T">
import { PaginationButton } from '@/components/shared/pagination';
import type { PaginateData } from '@/components/shared/pagination/types';
import { Button } from '@/components/ui/button';
import { Pagination, PaginationContent, PaginationEllipsis } from '@/components/ui/pagination';
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        siblingCount?: number;
        paginateData: PaginateData<T>;
    }>(),
    {
        siblingCount: 1,
    },
);
</script>

<template>
    <Pagination
        :items-per-page="paginateData.per_page"
        :total="paginateData.total"
        :sibling-count="props.siblingCount"
        :default-page="paginateData.current_page"
        show-edges
        v-slot="{ page }"
    >
        <PaginationContent class="flex items-center gap-1">
            <PaginationContent v-slot="{ items }">
                <Link :href="paginateData.first_page_url" as-child>
                    <Button size="icon" variant="outline" class="cursor-pointer">
                        <ChevronsLeft />
                    </Button>
                </Link>
                <Link v-if="paginateData.prev_page_url" :href="paginateData.prev_page_url" as-child>
                    <Button size="icon" variant="outline" class="cursor-pointer">
                        <ChevronLeft />
                    </Button>
                </Link>
                <Button v-else size="icon" variant="outline" class="cursor-pointer" disabled>
                    <ChevronLeft />
                </Button>
                <template v-for="(item, index) in items" :key="index">
                    <PaginationButton
                        v-if="item.type === 'page'"
                        class="md:inline-flex"
                        :class="{
                            hidden: item.value !== page,
                        }"
                        :value="item.value"
                        :is-active="item.value === page"
                        :links="paginateData.links"
                    />
                    <PaginationEllipsis v-else class="hidden md:inline-flex" />
                </template>
                <Link v-if="paginateData.next_page_url" :href="paginateData.next_page_url" as-child>
                    <Button size="icon" variant="outline" class="cursor-pointer">
                        <ChevronRight />
                    </Button>
                </Link>
                <Button v-else size="icon" variant="outline" class="cursor-pointer" disabled>
                    <ChevronRight />
                </Button>
                <Link :href="paginateData.last_page_url" as-child>
                    <Button size="icon" variant="outline" class="cursor-pointer">
                        <ChevronsRight />
                    </Button>
                </Link>
            </PaginationContent>
        </PaginationContent>
    </Pagination>
</template>
