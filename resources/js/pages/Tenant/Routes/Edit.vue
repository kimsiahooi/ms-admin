<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import { SwitchOption } from '@/components/shared/switch';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { OperationFormDataType, OperationSelect, PlantWithDepartmentsWithOperations } from '@/components/view/Tenant/Routes';
import { useTenant } from '@/composables/useTenant';
import { useUuid } from '@/composables/useUuid';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Plant } from '@/types/Tenant/plants';
import { Department } from '@/types/Tenant/plants/departments';
import { Operation } from '@/types/Tenant/plants/departments/operations';
import { Route, Status, StatusLabel } from '@/types/Tenant/routes';
import { Head, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { computed } from 'vue';

interface CustomRoute extends Route {
    operations: (Operation & {
        pivot: {
            readonly id: string;
            route_id: Route['id'];
            operation_id: Operation['id'];
            sequence: number;
            created_at: Date | null;
            updated_at: Date | null;
        };
        department_id: Department['id'] | null;
        department:
            | (Department & {
                  plant_id: Plant['id'] | null;
                  plant: Plant | null;
              })
            | null;
    })[];
}

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    route: CustomRoute;
    plants: PlantWithDepartmentsWithOperations[];
    options: {
        switch: {
            statuses: SwitchOption[];
        };
    };
}>();

const { tenant } = useTenant();
const { uuid } = useUuid();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: 'Dashboard',
        href: route('dashboard', { tenant: tenant?.id || '' }),
    },
    {
        title: 'Routes',
        href: route('routes.index', { tenant: tenant?.id || '' }),
    },
    {
        title: props.route.name,
        href: '#',
    },
    {
        title: 'Edit',
        href: route('routes.edit', { tenant: tenant?.id || '', route: props.route.id }),
    },
]);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const operationRouteData = computed(() =>
    props.route.operations
        .filter((operation) => operation.department?.plant)
        .map((operation) => ({
            key: uuid(),
            plant_id: operation.department?.plant_id ?? undefined,
            department_id: operation.department_id ?? undefined,
            operation_id: operation.id,
        })),
);

const defaultOperationData = {
    plant_id: '',
    department_id: '',
    operation_id: '',
};

const form = useForm<{
    name: CustomRoute['name'];
    code: CustomRoute['code'];
    description?: Exclude<CustomRoute['description'], null>;
    status?: boolean;
    operations: OperationFormDataType[];
}>({
    name: props.route.name,
    code: props.route.code,
    description: props.route.description ?? undefined,
    status: props.route.status.switch ?? undefined,
    operations: operationRouteData.value.length ? operationRouteData.value : [{ key: uuid(), ...defaultOperationData }],
});

const submit = () =>
    form.put(route('routes.update', { tenant: tenant?.id || '', route: props.route.id }), {
        preserveScroll: true,
        preserveState: true,
    });

const add = () => {
    form.operations = [
        ...form.operations,
        {
            key: uuid(),
            ...defaultOperationData,
        },
    ];
};

const remove = (key: string) => {
    form.operations = form.operations.filter((operation) => operation.key !== key);
};
</script>

<template>
    <Head :title="route.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card :title="`Edit ${route.name}`">
                    <FormInput label="Name" :error="form.errors.name" v-model:model-value="form.name" />
                    <FormInput label="Code" :error="form.errors.code" v-model:model-value="form.code" />
                    <FormTextarea label="Description" :error="form.errors.description" v-model:model-value="form.description" />
                    <div class="space-y-3">
                        <div>
                            <Label class="mb-1">Operations:</Label>
                        </div>
                        <div class="space-y-3">
                            <div v-for="(operation, index) in form.operations" :key="operation.key" class="space-y-1">
                                <OperationSelect
                                    :key="operation.key"
                                    :plants="plants"
                                    :total-selected="form.operations.length"
                                    :curr-index="index"
                                    v-model:model-value="form.operations[index]"
                                    @remove="remove"
                                />
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <Button type="button" class="cursor-pointer" variant="outline" @click="add"><Plus /> Add Operation</Button>
                        </div>
                    </div>
                    <FormSwitch
                        v-if="form.status !== undefined"
                        :label="statusDisplay"
                        :error="form.errors.status"
                        v-model:model-value="form.status"
                    />
                    <FormButton type="submit" :disabled="form.processing" label="Update" :loading="form.processing" />
                </Card>
            </form>
        </Layout>
    </AppLayout>
</template>
