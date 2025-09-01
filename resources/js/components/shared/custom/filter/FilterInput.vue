<script setup lang="ts">
import { FilterContainer } from '@/components/shared/custom/filter';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineProps<{
    label: string;
    placeholder: string;
}>();

const model = defineModel<string>();
</script>

<template>
    <FilterContainer>
        <Label>{{ label }}:</Label>
        <Input class="w-full" :placeholder="placeholder" v-model:model-value="model" />
    </FilterContainer>
</template>
