<script setup lang="ts" generic="T extends AcceptableValue">
import { FormContainer, FormError } from '@/components/shared/custom/form';
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
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
const model = defineModel<AcceptableValue | AcceptableValue[]>();

const placeholder = computed(() => props.placeholder ?? `Select ${props.label}`);

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
        <Select
            v-bind="$attrs"
            :options="options"
            :placeholder="placeholder"
            v-model:model-value="model"
            :trigger-class="[
                'w-full',
                {
                    'border-destructive': error || existingErrors,
                },
            ]"
        />
        <FormError :error="error" :error-key="errorKey" />
    </FormContainer>
</template>
