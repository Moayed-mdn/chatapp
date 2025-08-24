import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
// window.Echo.private('chat') // Match the event's channel name
//   .listen('.message.sent', (e) => { // Note the dot prefix for custom event names
//         console.log("NEW MESSAGE", e);
//   });

// window.Echo.private('chat-room.1') // Match the event's channel name  /// the name of the channel
// .listen('NewChatMessage', (e) => { // Note the dot prefix for custom event names  // the name of the event 
//     console.log("NEW MESSAGE FROM chat-romm.1", e);
// });


