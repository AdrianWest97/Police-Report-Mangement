

<template>
  <div class="text-center ma-2">
    <v-snackbar
      v-model="$store.getters.getSnack.visible"
      right
    >
      {{$store.getters.getSnack.content }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="pink"
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

