<template>
  <v-app>
      <v-main>
          <track-report-dialog/>
              <router-view />

      </v-main>
  </v-app>
</template>

<script>
import TrackReportDialog from "./components/TrackReportDialog.vue"

export default {
  components: {
    TrackReportDialog
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
