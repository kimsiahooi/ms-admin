<script setup lang="ts">
import { FormContainer, FormError } from '@/components/shared/custom/form';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useError } from '@/composables/useError';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        label: string;
        type?: string;
        placeholder?: string;
        error?: string;
        errorKey?: string;
    }>(),
    {
        type: 'text',
    },
);

const { findErrors } = useError();
const model = defineModel<string | number>();

const placeholder = computed(() => props.placeholder ?? `Enter ${props.label}`);

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
        <Textarea
            v-bind="$attrs"
            :type="props.type"
            :placeholder="placeholder"
            v-model:model-value="model"
            :class="{
                'border-destructive': error || existingErrors,
            }"
        />
        <FormError :error="error" :error-key="errorKey" />
    </FormContainer>
</template>
