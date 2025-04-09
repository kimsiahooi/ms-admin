<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import type { AllPermission, Permission } from '@/types/Permission';
import { useForm } from '@inertiajs/vue3';

const { permissions } = defineProps<{
    permissions: Permission[];
}>();

const form = useForm({
    permissions: permissions.map((permission) => permission.name),
});

const showPermissions = (permission: AllPermission) => form.permissions.includes(permission);

const permissionHandler = (permission: AllPermission) => {
    if (form.permissions.includes(permission)) {
        form.permissions = form.permissions.filter((p) => p !== permission);
    } else {
        form.permissions.push(permission);
    }
};

const submit = () => console.log(form);
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Permissions</CardTitle>
        </CardHeader>
        <CardContent>
            <form @submit.prevent="submit">
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div>
                        <h6 class="mb-3 font-bold">Role</h6>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('View Role')" @click="permissionHandler('View Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    View Role
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Create Role')" @click="permissionHandler('Create Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Create Role
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Update Role')" @click="permissionHandler('Update Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Update Role
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Force Delete Role')" @click="permissionHandler('Force Delete Role')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Force Delete Role
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="mb-3 font-bold">User</h6>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('View User')" @click="permissionHandler('View User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    View User
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Create User')" @click="permissionHandler('Create User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Create User
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Update User')" @click="permissionHandler('Update User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Update User
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Delete User')" @click="permissionHandler('Delete User')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Delete User
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="mb-3 font-bold">Tenant</h6>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('View Tenant')" @click="permissionHandler('View Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    View Tenant
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Create Tenant')" @click="permissionHandler('Create Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Create Tenant
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Update Tenant')" @click="permissionHandler('Update Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Update Tenant
                                </label>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox :model-value="showPermissions('Delete Tenant')" @click="permissionHandler('Delete Tenant')" />
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Delete Tenant
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
