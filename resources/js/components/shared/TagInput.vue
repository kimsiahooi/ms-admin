<script setup lang="ts" generic="T extends string">
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/ui/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import { useFilter } from 'reka-ui';
import { computed, ref } from 'vue';

const props = defineProps<{
    values: T[];
    modelValue: T[];
    placeholder: string;
}>();

const emit = defineEmits<{
    (event: 'pushValue', payload: T): void;
    (event: 'removeValue', payload: T): void;
}>();

const open = ref(false);
const searchTerm = ref('');

const { contains } = useFilter({ sensitivity: 'base' });
const filteredValues = computed(() => {
    const options = props.values.filter((i) => !props.modelValue.includes(i));
    return searchTerm.value ? options.filter((option) => contains(option, searchTerm.value)) : options;
});

const selectHandler = (value: T) => emit('pushValue', value);

const deleteHandler = (value: T) => emit('removeValue', value);
</script>

<template>
    <Combobox :model-value="props.modelValue" v-model:open="open" :ignore-filter="true">
        <ComboboxAnchor as-child>
            <TagsInput :model-value="props.modelValue" class="w-full gap-2 px-2">
                <div class="flex flex-wrap items-center gap-2">
                    <TagsInputItem v-for="item in props.modelValue" :key="item" :value="item">
                        <TagsInputItemText />
                        <TagsInputItemDelete @click="deleteHandler(item)" />
                    </TagsInputItem>
                </div>

                <ComboboxInput v-model="searchTerm" as-child>
                    <TagsInputInput
                        :placeholder="placeholder"
                        class="h-auto w-full min-w-[100px] border-none p-0 focus-visible:ring-0"
                        @keydown.enter.prevent
                    />
                </ComboboxInput>
            </TagsInput>

            <ComboboxList class="w-[--reka-popper-anchor-width]">
                <ComboboxEmpty />
                <ComboboxGroup>
                    <ComboboxItem
                        v-for="value in filteredValues"
                        :key="value"
                        :value="value"
                        @select.prevent="
                            () => {
                                searchTerm = '';
                                selectHandler(value);

                                if (filteredValues.length === 0) {
                                    open = false;
                                }
                            }
                        "
                    >
                        {{ value }}
                    </ComboboxItem>
                </ComboboxGroup>
            </ComboboxList>
        </ComboboxAnchor>
    </Combobox>
</template>
