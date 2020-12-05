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



new Vue({
  store,
  router,
  mode:'history',
  vuetify: new Vuetify({
     theme: {
    themes: {
      light: {
        primary: '#072E6F',
        secondary: '#BA5E5F',
        accent: '#D97B7C',
        error: '#b71c1c',
      },
    },
  },
  }),
  render: (h) => h(App),
}).$mount("#app");
