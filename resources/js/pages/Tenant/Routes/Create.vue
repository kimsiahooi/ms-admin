<script setup lang="ts">
import { Card } from '@/components/shared/card';
import { Layout } from '@/components/shared/custom/container';
import { FormButton, FormInput, FormSwitch, FormTextarea } from '@/components/shared/custom/form';
import { SwitchOption } from '@/components/shared/switch';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { PlantWithDepartmentsWithTasks, TaskFormDataType, TaskSelect } from '@/components/view/Tenant/Routes';
import { useTenant } from '@/composables/useTenant';
import { useUuid } from '@/composables/useUuid';
import AppLayout from '@/layouts/Tenant/AppLayout.vue';
import AppMainLayout from '@/layouts/Tenant/AppMainLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Status, StatusLabel } from '@/types/Tenant/routes';
import { Head, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import slug from 'slug';
import { computed, watch } from 'vue';

defineOptions({
    layout: AppMainLayout,
});

const props = defineProps<{
    plants: PlantWithDepartmentsWithTasks[];
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
        title: 'Create',
        href: route('routes.create', { tenant: tenant?.id || '' }),
    },
]);

const defaultStatus = computed<(typeof props.options.switch.statuses)[number]['value']>(
    () => !!props.options.switch.statuses.find((status) => status.is_default)?.value,
);

const statusDisplay = computed(
    () => props.options.switch.statuses.find((status) => status.value === form.status)?.name ?? StatusLabel[Status.INACTIVE],
);

const form = useForm<{
    name: string;
    code: string;
    description: string;
    status: boolean;
    tasks: TaskFormDataType[];
}>({
    name: '',
    code: '',
    description: '',
    status: defaultStatus.value,
    tasks: [
        {
            key: uuid(),
            plant_id: '',
            department_id: '',
            task_id: '',
        },
    ],
});

const submit = () =>
    form.post(route('routes.store', { tenant: tenant?.id || '' }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
    });

const add = () => {
    form.tasks = [
        ...form.tasks,
        {
            key: uuid(),
            plant_id: '',
            department_id: '',
            task_id: '',
        },
    ];
};

const remove = (key: string) => {
    form.tasks = form.tasks.filter((task) => task.key !== key);
};

watch(
    () => form.name,
    (newName) => {
        form.code = slug(newName);
    },
);
</script>

<template>
    <Head title="Create Route" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <form @submit.prevent="submit">
                <Card title="Create Route">
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
                    <FormButton type="submit" :disabled="form.processing" :loading="form.processing" />
                </Card>
            </form>
        </Layout>
    </AppLayout>
</template>
