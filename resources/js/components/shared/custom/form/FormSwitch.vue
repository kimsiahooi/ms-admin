<script setup lang="ts">
import { FormContainer } from '@/components/shared/custom/form';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';

defineProps<{
    label?: string;
    error?: string;
}>();

const model = defineModel<boolean>();
</script>

<template>
    <FormContainer>
        <Label class="mb-1">Status</Label>
        <div class="flex items-center space-x-2">
            <Switch class="cursor-pointer" v-model:model-value="model" />
            <Label>{{ label }}</Label>
        </div>
        <p v-if="error" class="text-destructive">{{ error }}</p>
    </FormContainer>
</template>
