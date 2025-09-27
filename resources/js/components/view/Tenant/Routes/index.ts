import { FormDataType } from '@/types';
import { Plant } from '@/types/Tenant/plants';
import { Department, DepartmentWithOperations } from '@/types/Tenant/plants/departments';
import { Operation } from '@/types/Tenant/plants/departments/operations';

export { default as OperationSelect } from './OperationSelect.vue';

export interface OperationForm {
    readonly key: string;
    plant_id?: Plant['id'];
    department_id?: Department['id'];
    operation_id?: Operation['id'];
}

export type OperationFormDataType = OperationForm & FormDataType;

export interface PlantWithDepartmentsWithOperations extends Plant {
    departments: DepartmentWithOperations[];
}
