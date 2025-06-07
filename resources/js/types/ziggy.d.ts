import type { route as routeHelper } from 'ziggy-js';

declare global {
    let route: typeof routeHelper;
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof routeHelper;
    }
}
