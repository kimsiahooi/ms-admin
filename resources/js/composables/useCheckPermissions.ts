import type { SharedData } from '@/types';
import type { AllPermission } from '@/types/Permission';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<SharedData>();

const authPermissions = computed(() => page.props.auth.permissions);

export const useCheckPermissions = () => {
    return (permissions: AllPermission[]) =>
        !permissions.filter((permission) => !authPermissions.value?.map((authPermission) => authPermission.name).includes(permission)).length;
};
