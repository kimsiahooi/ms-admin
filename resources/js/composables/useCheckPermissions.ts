import type { AllPermission } from '@/types/Permission';

export const useCheckPermissions = () => {
    return (permissions: AllPermission[] | null | undefined, checkPermissions: AllPermission[]) =>
        !!permissions?.filter((permission) => checkPermissions.includes(permission)).length;
};
