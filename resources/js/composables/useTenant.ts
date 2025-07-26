import type { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<AppPageProps>();
const tenant = computed(() => page.props.tenant);

export const useTenant = () => {
    return { tenant: tenant.value };
};
