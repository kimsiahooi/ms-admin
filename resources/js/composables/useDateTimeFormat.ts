import { format } from 'date-fns';

export const useDateTimeFormat = () => {
    return (date: Date) => format(date, 'dd MMM, yyyy hh:mm:ssaaa');
};
