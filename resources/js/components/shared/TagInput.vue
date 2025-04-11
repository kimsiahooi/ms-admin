<script setup lang="ts">
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/ui/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import { useFilter } from 'reka-ui';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    values: string[];
    modelValue: string[];
    placeholder: string;
}>();

const emit = defineEmits(['updateValues']);

const modelValue = ref(props.modelValue);
const open = ref(false);
const searchTerm = ref('');

const { contains } = useFilter({ sensitivity: 'base' });
const filteredValues = computed(() => {
    const options = props.values.filter((i) => !modelValue.value.includes(i));
    return searchTerm.value ? options.filter((option) => contains(option, searchTerm.value)) : options;
});

watch(modelValue, (newValue) => {
    emit('updateValues', newValue);
});
</script>

<template>
    <Combobox v-model="modelValue" v-model:open="open" :ignore-filter="true">
        <ComboboxAnchor as-child>
            <TagsInput v-model="modelValue" class="w-full gap-2 px-2">
                <div class="flex flex-wrap items-center gap-2">
                    <TagsInputItem v-for="item in modelValue" :key="item" :value="item">
                        <TagsInputItemText />
                        <TagsInputItemDelete />
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
                            (ev) => {
                                if (typeof ev.detail.value === 'string') {
                                    searchTerm = '';
                                    modelValue.push(ev.detail.value);
                                }

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
