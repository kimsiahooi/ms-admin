<script setup lang="ts" generic="T extends AcceptableValue">
import { Combobox } from '@/components/shared/combobox';
import { FormContainer, FormError } from '@/components/shared/custom/form';
import type { SelectOption } from '@/components/shared/select';
import { Label } from '@/components/ui/label';
import { useError } from '@/composables/useError';
import type { AcceptableValue } from 'reka-ui';
import { computed } from 'vue';

const props = defineProps<{
    label: string;
    placeholder?: string;
    error?: string;
    errorKey?: string;
    options: SelectOption<T>[];
}>();

const { findErrors } = useError();
const model = defineModel<T | T[]>();

const placeholderValue = computed(() =>
    Array.isArray(model.value) ? `${model.value?.length} selected` : props.options.find((option) => option.value === model.value)?.name,
);

const commandPlaceholder = computed(() => props.placeholder ?? `Select ${props.label}`);

const computedPlaceholder = computed(() => placeholderValue.value || commandPlaceholder.value);

const existingErrors = computed(() => props.errorKey && findErrors(props.errorKey));
</script>

<template>
    <FormContainer>
        <Label
            :class="{
                'text-destructive': error || existingErrors,
            }"
        >
            {{ label }}:
        </Label>
        <div class="flex items-center gap-2">
            <Combobox
                v-bind="$attrs"
                :options="options"
                :placeholder="computedPlaceholder"
                :command-placeholder="commandPlaceholder"
                v-model:model-value="model"
                :class="{
                    '!border-destructive': error || existingErrors,
                    '!text-muted-foreground': (Array.isArray(model) && !model.length) || !model,
                }"
            />
            <slot />
        </div>
        <FormError :error="error" :error-key="errorKey" />
    </FormContainer>
</template>
