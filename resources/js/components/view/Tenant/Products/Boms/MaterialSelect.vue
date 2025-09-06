<script setup lang="ts">
import { FormInput, FormSelect } from '@/components/shared/custom/form';
import type { SelectOption } from '@/components/shared/select';
import { Button } from '@/components/ui/button';
import { useUuid } from '@/composables/useUuid';
import type { Material } from '@/types/Tenant/materials';
import { Minus } from 'lucide-vue-next';
import { computed, reactive, ref, watch } from 'vue';
import type { MaterialConfig } from '.';

type PartialMaterial = Pick<Material, 'id' | 'name' | 'unit_type' | 'unit_type_label'>;

const props = defineProps<{
    materialOptions: SelectOption<PartialMaterial>[];
    unitTypeOptions: SelectOption<Material['unit_type']>[];
    totalSelected: number;
    currIndex: number;
}>();

const model = defineModel<MaterialConfig<PartialMaterial>>();
const emits = defineEmits<{
    remove: [value: string];
}>();

const { uuid } = useUuid();

const selected = ref<Material['id'] | ''>(model.value?.data?.id || '');

const filteredOptions = computed<SelectOption<Material['id']>[]>(() =>
    props.materialOptions.map((material) => ({ ...material, value: material.value.id })),
);

const form = reactive<MaterialConfig<PartialMaterial>>({
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
        <div class="flex flex-col gap-2 md:flex-row">
            <div class="flex-1">
                <FormSelect label="Material" :options="filteredOptions" v-model:model-value="selected" :error-key="`materials.${currIndex}.id`" />
            </div>
            <div class="flex-1">
                <FormSelect
                    label="Unit Type"
                    :options="unitTypeOptions"
                    v-model:model-value="form.unit_type"
                    :error-key="`materials.${currIndex}.unit_type`"
                />
            </div>
            <div class="flex-1">
                <FormInput
                    label="Quantity"
                    type="number"
                    v-model:model-value="form.quantity"
                    min="0.01"
                    step=".01"
                    :error-key="`materials.${currIndex}.quantity`"
                >
                    <div v-if="totalSelected > 1">
                        <Button type="button" class="cursor-pointer" size="icon" variant="destructive" @click="removeMaterial">
                            <Minus />
                        </Button>
                    </div>
                </FormInput>
            </div>
        </div>
    </div>
</template>
