<script setup lang="ts">
import { FormContainer, FormError } from '@/components/shared/custom/form';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        label: string;
        type?: string;
        placeholder?: string;
        error?: string;
    }>(),
    {
        type: 'text',
    },
);

const model = defineModel<string | number>();

const placeholder = computed(() => props.placeholder ?? `Enter ${props.label}`);
</script>

<template>
    <FormContainer>
        <Label>{{ label }}:</Label>
        <Textarea
            :type="props.type"
            :placeholder="placeholder"
            v-model:model-value="model"
            :class="{
                'border-destructive': error,
            }"
        />
        <FormError :error="error" />
    </FormContainer>
</template>
