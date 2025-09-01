<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const props = withDefaults(
    defineProps<{
        title?: string;
        description?: string;
    }>(),
    {
        title: 'Filter',
    },
);

const emits = defineEmits<{
    search: [];
    reset: [];
}>();
</script>

<template>
    <form @submit.prevent="emits('search')">
        <Card>
            <CardHeader>
                <CardTitle>{{ props.title }}</CardTitle>
                <CardDescription>{{ description }}</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="space-y-3">
                    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                        <slot />
                    </div>
                    <div class="flex justify-end gap-2">
                        <Button class="cursor-pointer" type="submit">Search</Button>
                        <Button class="cursor-pointer" type="reset" variant="secondary" @click="emits('reset')"> Reset </Button>
                    </div>
                </div>
            </CardContent>
        </Card>
    </form>
</template>
