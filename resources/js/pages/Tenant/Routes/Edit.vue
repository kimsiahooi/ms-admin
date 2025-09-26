<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import { SwitchOption } from '@/components/shared/switch';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { PlantWithOperationsWithTasks, TaskFormDataType, TaskSelect } from '@/components/view/Tenant/Routes';
import { useTenant } from '@/composables/useTenant';
import { useUuid } from '@/composables/useUuid';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Plant } from '@/types/Tenant/plants';
import { Operation } from '@/types/Tenant/plants/operations';
import { Task } from '@/types/Tenant/plants/operations/tasks';
import { Route, Status, StatusLabel } from '@/types/Tenant/routes';
import { Head, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { computed } from 'vue';

interface CustomRoute extends Route {
    tasks: (Task & {
        pivot: {
            id: string;
            route_id: Route['id'];
            task_id: Task['id'];
            created_at: Date | null;
            updated_at: Date | null;
        };
        operation_id: Operation['id'] | null;
        operation:
            | (Operation & {
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
    plants: PlantWithOperationsWithTasks[];
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

const routeTaskData = computed(() =>
    props.route.tasks
        .filter((task) => task.operation?.plant)
        .map((task) => ({
            key: uuid(),
            plant_id: task.operation?.plant_id ?? undefined,
            operation_id: task.operation_id ?? undefined,
            task_id: task.id,
        })),
);

const defaultTaskData = {
    plant_id: '',
    operation_id: '',
    task_id: '',
};

const form = useForm<{
    name: CustomRoute['name'];
    code: CustomRoute['code'];
    description?: Exclude<CustomRoute['description'], null>;
    status?: boolean;
    tasks: TaskFormDataType[];
}>({
    name: props.route.name,
    code: props.route.code,
    description: props.route.description ?? undefined,
    status: props.route.status.switch ?? undefined,
    tasks: routeTaskData.value.length ? routeTaskData.value : [{ key: uuid(), ...defaultTaskData }],
});

const submit = () =>
    form.put(route('routes.update', { tenant: tenant?.id || '', route: props.route.id }), {
        preserveScroll: true,
        preserveState: true,
    });

const add = () => {
    form.tasks = [
        ...form.tasks,
        {
            key: uuid(),
            ...defaultTaskData,
        },
    ];
};

const remove = (key: string) => {
    form.tasks = form.tasks.filter((task) => task.key !== key);
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
                            <Label class="mb-1">Tasks:</Label>
                        </div>
                        <div class="space-y-3">
                            <div v-for="(task, index) in form.tasks" :key="task.key" class="space-y-1">
                                <TaskSelect
                                    :key="task.key"
                                    :plants="plants"
                                    :total-selected="form.tasks.length"
                                    :curr-index="index"
                                    v-model:model-value="form.tasks[index]"
                                    @remove="remove"
                                />
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <Button type="button" class="cursor-pointer" variant="outline" @click="add"><Plus /> Add Task</Button>
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
