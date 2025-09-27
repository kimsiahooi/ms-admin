<script setup lang="ts">
import { FormCombobox } from '@/components/shared/custom/form';
import { SelectOption } from '@/components/shared/select';
import { Button } from '@/components/ui/button';
import { useUuid } from '@/composables/useUuid';
import { Minus } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { OperationForm, OperationFormDataType, PlantWithDepartmentsWithOperations } from '.';

const props = defineProps<{
    plants: PlantWithDepartmentsWithOperations[];
    totalSelected: number;
    currIndex: number;
}>();

const model = defineModel<OperationFormDataType>();
const { uuid } = useUuid();

const emits = defineEmits<{
    remove: [value: string];
}>();

const form = ref<OperationForm>({
    key: model.value?.key ?? uuid(),
    plant_id: model.value?.plant_id,
    department_id: model.value?.department_id,
    operation_id: model.value?.operation_id,
});

const plantOptions = computed<SelectOption<PlantWithDepartmentsWithOperations['id']>[]>(() =>
    props.plants.map((plant) => ({ name: plant.name, value: plant.id })),
);

const departmentOptions = computed<SelectOption<PlantWithDepartmentsWithOperations['departments'][number]['id']>[]>(
    () =>
        props.plants
            .find((plant) => plant.id === form.value.plant_id)
            ?.departments.map((department) => ({ name: department.name, value: department.id })) ?? [],
);

const operationOptions = computed<SelectOption<PlantWithDepartmentsWithOperations['departments'][number]['operations'][number]['id']>[]>(
    () =>
        props.plants
            .find((plant) => plant.id === form.value.plant_id)
            ?.departments.find((department) => department.id === form.value.department_id)
            ?.operations.map((operation) => ({ name: operation.name, value: operation.id })) ?? [],
);

const remove = () => {
    emits('remove', form.value.key);
};

watch(
    () => form.value.plant_id,
    () => {
        form.value.department_id = undefined;
    },
);

watch(
    () => form.value.department_id,
    () => {
        form.value.operation_id = undefined;
    },
);

watch(
    model,
    (newVal) => {
        if (newVal) {
            form.value = newVal;
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
                <FormCombobox
                    label="Plant"
                    :options="plantOptions"
                    v-model:model-value="form.plant_id"
                    :error-key="`operations.${currIndex}.plant_id`"
                />
            </div>
            <div class="flex-1">
                <FormCombobox
                    label="Department"
                    :options="departmentOptions"
                    v-model:model-value="form.department_id"
                    :error-key="`operations.${currIndex}.department_id`"
                />
            </div>
            <div class="flex-1">
                <FormCombobox
                    label="Operation"
                    :options="operationOptions"
                    v-model:model-value="form.operation_id"
                    :error-key="`operations.${currIndex}.operation_id`"
                >
                    <div v-if="totalSelected > 1">
                        <Button type="button" class="cursor-pointer" size="icon" variant="destructive" @click="remove">
                            <Minus />
                        </Button>
                    </div>
                </FormCombobox>
            </div>
        </div>
    </div>
</template>
