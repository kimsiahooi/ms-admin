<script setup lang="ts" generic="T extends AcceptableValue">
import { SelectOption } from '@/components/shared/select';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Check, ChevronDown } from 'lucide-vue-next';
import { AcceptableValue } from 'reka-ui';
import { computed, onMounted, onUnmounted, ref, useTemplateRef } from 'vue';

const props = defineProps<{
    placeholder: string;
    commandPlaceholder: string;
    options: SelectOption<T>[];
    multiple?: boolean;
}>();

const model = defineModel<T | T[]>();
const openModel = defineModel<boolean>('open');
const buttonEl = useTemplateRef<{ button: { $el: HTMLButtonElement } }>('buttonEl');
const buttonWidth = ref(300);

const resize = () => {
    const width = buttonEl.value?.button.$el.offsetWidth;

    if (width) {
        buttonWidth.value = width;
    }
};

const optionIncluded = computed(
    () => (option: SelectOption<T>) => (Array.isArray(model.value) ? model.value?.includes(option.value) : model.value === option.value),
);

const select = () => {
    if (!props.multiple && openModel.value) {
        openModel.value = false;
    }
};

onMounted(() => {
    resize();
    window.addEventListener('resize', resize);
});

onUnmounted(() => {
    window.removeEventListener('resize', resize);
});
</script>

<template>
    <Popover v-model:open="openModel">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                class="w-full justify-between"
                ref="buttonEl"
                :class="[options.length ? 'cursor-pointer' : 'cursor-not-allowed']"
                :disabled="!options.length"
                v-bind="$attrs"
            >
                {{ placeholder }}
                <ChevronDown />
            </Button>
        </PopoverTrigger>
        <PopoverContent
            class="p-0"
            align="center"
            :style="{
                width: `${buttonWidth}px`,
            }"
        >
            <Command v-model:model-value="model" :multiple="multiple">
                <CommandInput :placeholder="commandPlaceholder" />
                <CommandList>
                    <CommandEmpty>No results found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem v-for="option in options" :key="option.name" :value="option.value" class="justify-between" @select="select">
                            {{ option.name }}
                            <Check v-if="optionIncluded(option)" />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
