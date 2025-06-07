import { useDateFormat } from '@vueuse/core';

export const useFormatDateTime = () => {
    const formatDateTime = (datetime: string | Date | null | undefined, format = 'YYYY-MM-DD hh:mma') =>
        datetime ? useDateFormat(datetime, format).value : datetime;

    return { formatDateTime };
};
