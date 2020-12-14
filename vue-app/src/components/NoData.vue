<template>
      <v-card class="m-5" flat>
        <v-card-text>
     <p>Nothing found. Click below to refresh</p>
      <v-btn
        color="primary"
        @click="btnClickHandler"
        text
      >
        Refresh
        <v-icon>
          mdi-refresh
        </v-icon>
      </v-btn>
        </v-card-text>
      </v-card>
</template>

<script>
export default {
props: {
btnClickHandler: {
type: Function,
required:true
}
}
}
</script>

<style>

</style>
