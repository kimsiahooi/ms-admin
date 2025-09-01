<script setup lang="ts">
import { FilterContainer } from '@/components/shared/custom/filter';
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import { Label } from '@/components/ui/label';

defineProps<{
    label: string;
    placeholder: string;
    options: SelectOption[];
    multiple?: boolean;
}>();

const model = defineModel<string>();
</script>

<template>
    <FilterContainer>
        <Label>{{ label }}:</Label>
        <Select :options="options" :placeholder="placeholder" trigger-class="w-full" v-model:model-value="model" :multiple="multiple" />
    </FilterContainer>
</template>
