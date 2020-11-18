<template>
  <v-app>
    <div  id="app">
          <Navigation />

    </div>
    <router-view />
  </v-app>
</template>

<script>
import Navigation from "./components/Navigation.vue";
export default {
  components: {
    Navigation
  },
  mounted() {
    this.$store.commit("LOGIN", !!localStorage.getItem("token"));
  }
};
</script>

<style >
.h-screen {
  height: 100vh;
}
</style>
