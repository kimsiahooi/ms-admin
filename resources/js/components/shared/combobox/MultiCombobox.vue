<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
    ComboboxTrigger,
} from '@/components/ui/combobox';
import { cn } from '@/lib/utils';
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next';
import type { AcceptableValue } from 'reka-ui';
import { ref, watch } from 'vue';
import type { SelectOption } from '../select/types';

const props = defineProps<{
    options: SelectOption[];
    placeholder: string;
    emptyPlaceholder: string;
}>();

const model = defineModel<AcceptableValue[] | undefined>();

const value = ref<SelectOption[] | undefined>(props.options.filter((option) => model.value?.includes(option.value)));

const emits = defineEmits<{
    'update:value': [AcceptableValue[]];
}>();

watch(value, (newValue) => {
    if (newValue) {
        model.value = newValue.map((v) => v.value);
    }
});

watch(model, (newValue) => {
    if (newValue) {
        emits('update:value', newValue);
    }
});
</script>

<template>
    <Combobox v-model="value" by="name" multiple>
        <ComboboxAnchor as-child>
            <ComboboxTrigger as-child>
                <Button variant="outline" class="w-full justify-between">
                    {{ value?.length ? value.map((v) => v.name).join(', ') : placeholder }}
                    <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                </Button>
            </ComboboxTrigger>
        </ComboboxAnchor>

        <ComboboxList>
            <div class="relative w-full max-w-sm items-center">
                <ComboboxInput class="h-10 rounded-none border-0 border-b pl-9 focus-visible:ring-0" :placeholder="placeholder" />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-3">
                    <Search class="text-muted-foreground size-4" />
                </span>
            </div>

            <ComboboxEmpty> {{ emptyPlaceholder }} </ComboboxEmpty>

            <ComboboxGroup>
                <ComboboxItem v-for="option in options" :key="option.name" :value="option">
                    {{ option.name }}
                    <ComboboxItemIndicator>
                        <Check :class="cn('ml-auto h-4 w-4')" />
                    </ComboboxItemIndicator>
                </ComboboxItem>
            </ComboboxGroup>
        </ComboboxList>
    </Combobox>
</template>
