<script setup lang="ts">
import type { SelectOption } from '@/components/shared/select';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { AcceptableValue } from 'reka-ui';
import { type HTMLAttributes, watch } from 'vue';

defineProps<{
    options: SelectOption[];
    placeholder: string;
    triggerClass?: HTMLAttributes['class'];
}>();

const model = defineModel<AcceptableValue[] | undefined>();

const emit = defineEmits<{
    change: [value: AcceptableValue[]];
}>();

watch(model, (newModel) => {
    if (newModel) {
        emit('change', newModel);
    }
});
</script>

<template>
    <Select v-model="model" multiple :disabled="!options.length">
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
