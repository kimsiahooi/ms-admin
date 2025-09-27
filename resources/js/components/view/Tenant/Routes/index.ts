import { FormDataType } from '@/types';
import { Plant } from '@/types/Tenant/plants';
import { Department, DepartmentWithTasks } from '@/types/Tenant/plants/departments';
import { Task } from '@/types/Tenant/plants/departments/tasks';

export { default as TaskSelect } from './TaskSelect.vue';

export interface TaskForm {
    readonly key: string;
    plant_id?: Plant['id'];
    department_id?: Department['id'];
    task_id?: Task['id'];
}

export type TaskFormDataType = TaskForm & FormDataType;

export interface PlantWithDepartmentsWithTasks extends Plant {
    departments: DepartmentWithTasks[];
}
