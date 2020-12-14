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
import './broadcaster.js';


// import * as VueGoogleMaps from 'vue2-google-maps'

// Vue.use(VueGoogleMaps, {
//   load: {
//     key: 'AIzaSyBUL3HDBpqj2dRlG_2dH8p1P7AAhTk-TuY',
    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)

    //// If you want to set the version, you can do so:
    // v: '3.26',
 // },

  //// If you intend to programmatically custom event listener code
  //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
  //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
  //// you might need to turn this on.
  // autobindAllEvents: false,

  //// If you want to manually install components, e.g.
  //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
  //// Vue.component('GmapMarker', GmapMarker)
  //// then disable the following:
  // installComponents: true,
//})

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

