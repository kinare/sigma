import Vue from 'vue'
import { InertiaApp } from '@inertiajs/inertia-vue'
import vuetify from "./plugins/vuetify"
import { InertiaProgress } from '@inertiajs/progress'

Vue.config.productionTip = false;

Vue.use(InertiaApp);
InertiaProgress.init()

const app = document.getElementById('app');

Vue.mixin({ methods: { route: window.route } });

new Vue({
    vuetify,
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => require(`./Pages/${name}`).default,
        },
    }),
}).$mount(app)
