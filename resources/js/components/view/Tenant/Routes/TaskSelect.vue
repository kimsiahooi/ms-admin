<script setup lang="ts">
import { FormCombobox } from '@/components/shared/custom/form';
import { SelectOption } from '@/components/shared/select';
import { Button } from '@/components/ui/button';
import { useUuid } from '@/composables/useUuid';
import { Minus } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { PlantWithDepartmentsWithTasks, TaskForm, TaskFormDataType } from '.';

const props = defineProps<{
    plants: PlantWithDepartmentsWithTasks[];
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
    department_id: model.value?.department_id,
    task_id: model.value?.task_id,
});

const plantOptions = computed<SelectOption<PlantWithDepartmentsWithTasks['id']>[]>(() =>
    props.plants.map((plant) => ({ name: plant.name, value: plant.id })),
);

const departmentOptions = computed<SelectOption<PlantWithDepartmentsWithTasks['departments'][number]['id']>[]>(
    () =>
        props.plants
            .find((plant) => plant.id === form.value.plant_id)
            ?.departments.map((department) => ({ name: department.name, value: department.id })) ?? [],
);

const taskOptions = computed<SelectOption<PlantWithDepartmentsWithTasks['departments'][number]['tasks'][number]['id']>[]>(
    () =>
        props.plants
            .find((plant) => plant.id === form.value.plant_id)
            ?.departments.find((department) => department.id === form.value.department_id)
            ?.tasks.map((task) => ({ name: task.name, value: task.id })) ?? [],
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
                    label="Department"
                    :options="departmentOptions"
                    v-model:model-value="form.department_id"
                    :error-key="`tasks.${currIndex}.department_id`"
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
