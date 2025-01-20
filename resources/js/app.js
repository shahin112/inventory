import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
 
import "../css/app.css";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

createInertiaApp({
    title: (title) => `My app ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
          
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("Head", Head)
            .component("Link", Link)
            .mount(el);
    },

    progress: {
        color: "green",
        includeCSS: true,
        showSpinner: false,
    },
});
