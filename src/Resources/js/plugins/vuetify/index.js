import Vue from "vue";
import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css';

Vue.use(Vuetify);

const opts = {
    theme: {
        themes: {
            light: {
                primary: '#113861',
                secondary: '#7c1c1f',
                accent: '#8c9eff',
                error: '#b71c1c',
            },
        },
    },
};

export default new Vuetify(opts);
