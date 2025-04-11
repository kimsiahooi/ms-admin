import type { SharedData } from '@/types';
import type { AllPermission } from '@/types/Permission';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<SharedData>();

const authPermissions = computed(() => page.props.auth.permissions?.map((authPermission) => authPermission.name));

export const useCheckPermissions = () => {
    const checkPermissions = (permissions: AllPermission[]) =>
        permissions.length ? !permissions.filter((permission) => !authPermissions.value?.includes(permission)).length : false;

    const checkAnyPermissions = (permissions: AllPermission[]) =>
        permissions.length ? permissions.filter((permission) => authPermissions.value?.includes(permission)).length : false;

    return { checkPermissions, checkAnyPermissions };
};
