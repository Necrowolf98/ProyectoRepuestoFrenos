
import Vue from 'vue';
import router from './routes';
import Vuetify from './vuetify';
import { Button, Notification, MessageBox } from 'element-ui';

import 'element-ui/lib/theme-chalk/index.css';

require('./bootstrap');

window.Vue = require('vue').default;
window.Fire = new Vue();

export const EventBus = new Vue();
window.EventBus = EventBus;

Vue.component('App', require('./components/App.vue').default);
Vue.component('Auth', require('./components/Auth.vue').default);

Vue.use(Button);
Vue.prototype.$notify = Notification;
Vue.prototype.$msgbox = MessageBox
Vue.prototype.$alert = MessageBox.alert
Vue.prototype.$confirm = MessageBox.confirm
Vue.prototype.$prompt = MessageBox.prompt

const app = new Vue({
    el: '#app',
    router,
    vuetify: Vuetify
});
