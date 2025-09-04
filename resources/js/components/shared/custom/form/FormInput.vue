<script setup lang="ts">
import { FormContainer, FormError } from '@/components/shared/custom/form';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
        <div class="flex items-center gap-2">
            <Input
                v-bind="$attrs"
                :type="props.type"
                :placeholder="placeholder"
                v-model:model-value="model"
                :class="{
                    'border-destructive': error || existingErrors,
                }"
            />
            <slot />
        </div>
        <FormError :error="error" :error-key="errorKey" />
    </FormContainer>
</template>
