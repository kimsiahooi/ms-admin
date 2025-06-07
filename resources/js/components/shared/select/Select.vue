<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { AcceptableValue } from 'reka-ui';
import { type HTMLAttributes, watch } from 'vue';
import type { SelectOption } from './types';

defineProps<{
    options: SelectOption[];
    placeholder: string;
    triggerClass?: HTMLAttributes['class'];
}>();

const model = defineModel<AcceptableValue | undefined>();

const emit = defineEmits<{
    change: [value: AcceptableValue];
}>();

watch(model, () => {
    emit('change', model);
});
</script>

<template>
    <Select v-model="model">
        <SelectTrigger class="min-w-40" :class="triggerClass">
            <SelectValue :placeholder="placeholder" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="(option, index) in options" :key="index" :value="option.value">{{ option.name }}</SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
