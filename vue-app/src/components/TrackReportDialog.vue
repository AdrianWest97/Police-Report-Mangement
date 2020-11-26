<template>
  <v-row justify="center">
    <v-dialog
      v-model="$store.state.TrackReportDialog.visible"
      persistent
      max-width="500"
    >
      <v-card>
        <v-card-title class="headline">
         Enter your report reference number
        </v-card-title>
        <v-card-text>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Eneter 6 digit reference number"
        hide-details
        rounded
        :maxlength="max"
        @input=" search = search.toUpperCase();"
        @keyup="fetchReportStatus()"
      ></v-text-field>
    <v-sheet color="light" v-if="isSearching">
        <v-skeleton-loader class="mx-auto"   type="article"
></v-skeleton-loader>
    </v-sheet>

        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            text
            @click="closeDialog()"
          >
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  export default {
    data:()=>({
      search:'',
      isSearching:false,
      max:7
    }),
    methods:{
       closeDialog(){
        this.isSearching = false;
        this.search = '';
        this.$store.commit("SET_TRACK_REPORT_DIALOG",false)
      },
      fetchReportStatus(){
        if(this.search.length > 0){
            this.isSearching = true;
        }else{
           this.isSearching = false;
        }
      }
    }
  }
</script>
