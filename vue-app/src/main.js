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
import axios from "axios";




Vue.config.productionTip = false;

new Vue({
  store,
  router,
  vuetify:new Vuetify,
  render: (h) => h(App),
}).$mount("#app");
