<script setup lang="ts">
import { FormContainer, FormError } from '@/components/shared/custom/form';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { useError } from '@/composables/useError';
import { computed } from 'vue';

const props = defineProps<{
    label?: string;
    error?: string;
    errorKey?: string;
}>();

const { findErrors } = useError();
const model = defineModel<boolean>();

const existingErrors = computed(() => props.errorKey && findErrors(props.errorKey));
</script>

<template>
    <FormContainer>
        <Label class="mb-1">Status:</Label>
        <div class="flex items-center space-x-2">
            <Switch class="cursor-pointer" v-model:model-value="model" v-bind="$attrs" />
            <Label
                :class="{
                    'text-destructive': error || existingErrors,
                }"
            >
                {{ label }}
            </Label>
        </div>
        <FormError :error="error" :error-key="errorKey" />
    </FormContainer>
</template>
