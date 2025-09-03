<script setup lang="ts">
import { FilterContainer } from '@/components/shared/custom/filter';
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import { Label } from '@/components/ui/label';

defineProps<{
    label: string;
    placeholder: string;
    options: SelectOption[];
}>();

const model = defineModel<string | string[]>();
</script>

<template>
    <FilterContainer>
        <Label>{{ label }}:</Label>
        <Select v-bind="$attrs" :options="options" :placeholder="placeholder" trigger-class="w-full" v-model:model-value="model" />
    </FilterContainer>
</template>
