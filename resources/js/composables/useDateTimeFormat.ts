import { format } from 'date-fns';

export const useDateTimeFormat = () => {
    return (date: Date | null) => (date ? format(date, 'dd MMM, yyyy hh:mm:ssaaa') : date);
};
