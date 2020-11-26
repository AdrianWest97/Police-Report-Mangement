import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
Vue.use(Vuetify)
import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import '../src/assets/css/main.scss'
Vue.use(require('vue-moment'));
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

Vue.component('vue-phone-number-input', VuePhoneNumberInput);
Vue.config.productionTip = false;
console.log(process.env)
new Vue({
  store,
  router,
  vuetify:new Vuetify,
  render: (h) => h(App),
}).$mount("#app");
