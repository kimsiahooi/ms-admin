<script setup lang="ts" generic="T extends AcceptableValue">
import { FormContainer, FormError } from '@/components/shared/custom/form';
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import { Label } from '@/components/ui/label';
import type { AcceptableValue } from 'reka-ui';
import { computed } from 'vue';

const props = defineProps<{
    label: string;
    placeholder?: string;
    error?: string;
    options: SelectOption<T>[];
    multiple?: boolean;
}>();

const model = defineModel<AcceptableValue>();

const placeholder = computed(() => props.placeholder ?? `Select ${props.label}`);
</script>

<template>
    <FormContainer>
        <Label>{{ label }}:</Label>
        <Select
            :options="options"
            :placeholder="placeholder"
            v-model:model-value="model"
            :trigger-class="[
                'w-full',
                {
                    'border-destructive': error,
                },
            ]"
            :multiple="multiple"
        />
        <FormError :error="error" />
    </FormContainer>
</template>
