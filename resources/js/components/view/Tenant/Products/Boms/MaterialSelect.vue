<script setup lang="ts">
import { Select } from '@/components/shared/select';
import type { SelectOption } from '@/components/shared/select/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useUuid } from '@/composables/useUuid';
import type { Material } from '@/types/Tenant/materials';
import { Minus } from 'lucide-vue-next';
import { computed, reactive, ref, watch } from 'vue';
import type { MaterialConfig } from '.';

const props = defineProps<{
    materialOptions: SelectOption<Material>[];
    unitTypeOptions: SelectOption<Material['unit_type']>[];
    totalSelected: number;
}>();

const model = defineModel<MaterialConfig>();
const emits = defineEmits<{
    remove: [value: string];
}>();

const { uuid } = useUuid();

const selected = ref<Material['id'] | ''>(model.value?.data?.id || '');

const filteredOptions = computed<SelectOption<Material['id']>[]>(() =>
    props.materialOptions.map((material) => ({ ...material, value: material.value.id })),
);

const form = reactive<MaterialConfig>({
    key: model.value?.key || uuid(),
    data: model.value?.data || null,
    quantity: model.value?.quantity || '',
    unit_type: model.value?.unit_type || '',
});

const removeMaterial = () => {
    emits('remove', form.key);
};

watch(selected, (newVal) => {
    if (newVal) {
        form.data = props.materialOptions.find((material) => material.value.id === newVal)?.value || null;
    } else {
        form.data = null;
    }
});

watch(
    () => form.data,
    (newVal) => {
        if (newVal?.unit_type) {
            form.unit_type = newVal.unit_type;
        } else {
            form.unit_type = '';
        }
    },
    { deep: true },
);

watch(
    form,
    (newVal) => {
        if (model.value) {
            model.value = newVal;
        }
    },
    { deep: true },
);
</script>

<template>
    <div>
        <div class="flex items-center gap-2">
            <div class="flex-1">
                <Select :options="filteredOptions" placeholder="Select Material" v-model:model-value="selected" trigger-class="w-full" />
            </div>
            <div class="flex-1">
                <Select :options="unitTypeOptions" placeholder="Select Unit Type" v-model:model-value="form.unit_type" trigger-class="w-full" />
            </div>
            <div class="flex-1">
                <Input type="number" placeholder="Enter Quantity" v-model:model-value.number="form.quantity" min="0" step=".01" />
            </div>
            <div v-if="totalSelected > 1">
                <Button type="button" class="cursor-pointer" size="icon" variant="destructive" @click="removeMaterial">
                    <Minus />
                </Button>
            </div>
        </div>
    </div>
</template>
