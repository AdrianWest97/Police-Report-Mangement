

<template>
  <div class="text-center ma-2">
    <v-snackbar
      v-model="$store.getters.getSnack.visible"
      left
      :timeout="$store.getters.getSnack.timeout"
    >
      <v-icon color="success">mdi-checkbox-marked-circle</v-icon>
      {{$store.getters.getSnack.content }}

      <template v-slot:action="{ attrs }">
           <v-btn
          text
          v-bind="attrs"
          @click="$store.state.snackBar.visible = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
export default {
}
</script>

