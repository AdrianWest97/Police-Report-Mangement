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
Vue.config.productionTip = false;
console.log(process.env)
new Vue({
  store,
  router,
  mode:'history',
  vuetify:new Vuetify,
  render: (h) => h(App),
}).$mount("#app");
