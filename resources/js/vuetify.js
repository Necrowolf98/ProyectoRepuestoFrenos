import Vue from 'vue'
import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify/lib'

Vue.use(Vuetify);

/* import Vuetify, { VBtn, VApp, VAlert, VTextField, VCol } from 'vuetify/lib' */
/* Vue.use(Vuetify, {
    components: { VBtn, VApp, VAlert, VTextField, VCol },
}) */

const opts = {}

export default new Vuetify(opts)
