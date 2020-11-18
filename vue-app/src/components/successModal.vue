<template>
  <v-row justify="center">
    <v-dialog
      v-model="successDialog.visible"
      persistent
      max-width="390"
      style="z-index:9999"
    >
      <v-card>
        <v-card-title class="headline">
          {{successDialog.title}}
        </v-card-title>
        <v-card-text>{{successDialog.message}}</v-card-text>
        <div  class="m-auto text-center">
        <h4>Ref#:</h4>
        <h1 class="text-uppercase">{{successDialog.content}}</h1>
        </div>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="closeDialog()"
          >
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import { mapGetters } from 'vuex';
  export default {
    computed:{
      ...mapGetters(['successDialog'])
    },
      methods:{
        closeDialog(){
        this.$store.commit('SET_SUCCESS_DIALOG',{
          content:'',
          message:'',
          visible:false,
          title:''
        });
    }
      }
  }
</script>
