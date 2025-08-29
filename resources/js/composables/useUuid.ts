import { v4 as uuidv4 } from 'uuid';

export const useUuid = () => {
    const uuid = uuidv4;

    return { uuid };
};
