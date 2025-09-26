<script setup lang="ts">
import { FormCombobox } from '@/components/shared/custom/form';
import { SelectOption } from '@/components/shared/select';
import { Button } from '@/components/ui/button';
import { useUuid } from '@/composables/useUuid';
import { Minus } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { PlantWithOperationsWithTasks, TaskForm, TaskFormDataType } from '.';

const props = defineProps<{
    plants: PlantWithOperationsWithTasks[];
    totalSelected: number;
    currIndex: number;
}>();

const model = defineModel<TaskFormDataType>();
const { uuid } = useUuid();

const emits = defineEmits<{
    remove: [value: string];
}>();

const form = ref<TaskForm>({
    key: model.value?.key ?? uuid(),
    plant_id: model.value?.plant_id,
    operation_id: model.value?.operation_id,
    task_id: model.value?.task_id,
});

const plantOptions = computed<SelectOption<PlantWithOperationsWithTasks['id']>[]>(() =>
    props.plants.map((plant) => ({ name: plant.name, value: plant.id })),
);

const operationOptions = computed<SelectOption<PlantWithOperationsWithTasks['operations'][number]['id']>[]>(
    () =>
        props.plants
            .find((plant) => plant.id === form.value.plant_id)
            ?.operations.map((operation) => ({ name: operation.name, value: operation.id })) ?? [],
);

const taskOptions = computed<SelectOption<PlantWithOperationsWithTasks['operations'][number]['tasks'][number]['id']>[]>(
    () =>
        props.plants
            .find((plant) => plant.id === form.value.plant_id)
            ?.operations.find((operation) => operation.id === form.value.operation_id)
            ?.tasks.map((task) => ({ name: task.name, value: task.id })) ?? [],
);

const remove = () => {
    emits('remove', form.value.key);
};

watch(
    () => form.value.plant_id,
    () => {
        form.value.operation_id = undefined;
    },
);

watch(
    () => form.value.operation_id,
    () => {
        form.value.task_id = undefined;
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
                <FormCombobox label="Plant" :options="plantOptions" v-model:model-value="form.plant_id" :error-key="`tasks.${currIndex}.plant_id`" />
            </div>
            <div class="flex-1">
                <FormCombobox
                    label="Operation"
                    :options="operationOptions"
                    v-model:model-value="form.operation_id"
                    :error-key="`tasks.${currIndex}.operation_id`"
                />
            </div>
            <div class="flex-1">
                <FormCombobox label="Task" :options="taskOptions" v-model:model-value="form.task_id" :error-key="`tasks.${currIndex}.task_id`">
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
