import { FormDataType } from '@/types';
import { Plant } from '@/types/Tenant/plants';
import { Operation, OperationWithTasks } from '@/types/Tenant/plants/operations';
import { Task } from '@/types/Tenant/plants/operations/tasks';

export { default as TaskSelect } from './TaskSelect.vue';

export interface TaskForm {
    readonly key: string;
    plant_id?: Plant['id'];
    operation_id?: Operation['id'];
    task_id?: Task['id'];
}

export type TaskFormDataType = TaskForm & FormDataType;

export interface PlantWithOperationsWithTasks extends Plant {
    operations: OperationWithTasks[];
}
