

<template>
  <div class="text-center ma-2">
    <v-snackbar
      v-model="$store.getters.getSnack.visible"
      right
      :timeout="$store.getters.getSnack.timeout"
    >
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

