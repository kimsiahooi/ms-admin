import type { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<AppPageProps>();

const formErrors = computed(() => page.props.errors);

export const useError = () => {
    const findErrors = (k: string) => {
        const errors = Object.entries(formErrors.value)
            .filter(([key]) => key.startsWith(k))
            .map(([key, error]) => ({ key, message: error }));
        return errors.length ? errors : null;
    };

    return { findErrors };
};
