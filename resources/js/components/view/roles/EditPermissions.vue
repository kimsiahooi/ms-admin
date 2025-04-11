<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { useCheckPermissions } from '@/composables/useCheckPermissions';
import type { AllPermission, Permission } from '@/types/Permission';
import type { Role } from '@/types/Role';
import { useForm } from '@inertiajs/vue3';

interface RoleWithPermissions extends Role {
    permissions: Permission[];
}

interface Props {
    role: RoleWithPermissions;
}

const props = defineProps<Props>();

const { checkPermissions } = useCheckPermissions();

const form = useForm({
    permissions: props.role.permissions.map((permission) => permission.name),
});

const showPermissions = (permission: AllPermission) => form.permissions.includes(permission);

const permissionHandler = (permission: AllPermission) => {
    if (form.permissions.includes(permission)) {
        form.permissions = form.permissions.filter((p) => p !== permission);
    } else {
        form.permissions.push(permission);
    }
};

const submit = () => form.put(route('roles.updatePermissions', props.role.id));
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Role Permissions</CardTitle>
        </CardHeader>
        <CardContent>
            <form @submit.prevent="submit">
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div>
                        <h6 class="mb-3 font-bold">Role</h6>
                        <div class="space-y-2">
                            <div v-if="checkPermissions(['View Role'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('View Role')" @click="permissionHandler('View Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    View Role
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Create Role'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Create Role')" @click="permissionHandler('Create Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Create Role
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Edit Role'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Edit Role')" @click="permissionHandler('Edit Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Edit Role
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Force Delete Role'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Force Delete Role')" @click="permissionHandler('Force Delete Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Permanant Delete Role
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="mb-3 font-bold">User</h6>
                        <div class="space-y-2">
                            <div v-if="checkPermissions(['View User'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('View User')" @click="permissionHandler('View User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    View User
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Create User'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Create User')" @click="permissionHandler('Create User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Create User
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Edit User'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Edit User')" @click="permissionHandler('Edit User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Edit User
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Delete User'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Delete User')" @click="permissionHandler('Delete User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Delete User
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Restore User'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Restore User')" @click="permissionHandler('Restore User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Restore User
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Force Delete User'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Force Delete User')" @click="permissionHandler('Force Delete User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Permanant Delete User
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="mb-3 font-bold">Tenant</h6>
                        <div class="space-y-2">
                            <div v-if="checkPermissions(['View Tenant'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('View Tenant')" @click="permissionHandler('View Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    View Tenant
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Create Tenant'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Create Tenant')" @click="permissionHandler('Create Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Create Tenant
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Edit Tenant'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Edit Tenant')" @click="permissionHandler('Edit Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Edit Tenant
                                </label>
                            </div>

                            <div v-if="checkPermissions(['Force Delete Tenant'])" class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Force Delete Tenant')" @click="permissionHandler('Force Delete Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Permanant Delete Tenant
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <Button type="submit">Update</Button>
                </div>
            </form>
        </CardContent>
    </Card>
</template>
