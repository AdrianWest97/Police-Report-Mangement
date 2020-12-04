<template>
  <v-row justify="center">
    <v-dialog
      v-model="$store.state.TrackReportDialog.visible"
       scrollable
        max-width="700px"
       overlay-color="#8c95a6"

    >
      <v-card
       :loading="isSearching">
        <v-card-title class="headline">

        <v-text-field
        v-model="search"
        label="Enter 6 digit reference number"
        hide-details
        rounded
        :maxlength="max"
        @input=" search = search.toUpperCase();"
      >
        <template slot="append">
           <v-icon v-show="search"  @click="fetchReportStatus()" medium> mdi-magnify </v-icon>
      </template>
      </v-text-field>
        </v-card-title>
        <v-card-text>
       <v-timeline v-show="results.length > 0" dense class="my-4">
      <v-timeline-item
        v-for="result in results"
        :key="result.id"
        large
        fill-dot
       class="white--text mb-12"
       :color="checkStatus(JSON.parse(result.data).status).color"

      >
         <template v-slot:icon>
          <small class="text-small">{{result.created_at | moment("MMM DD")}}</small><br>
        </template>
       <v-card  flat>
        <v-card-title>{{result.created_at | moment("ddd, MMM D YYYY")}}</v-card-title>
        <v-card-subtitle>
         <v-chip
        :color="checkStatus(JSON.parse(result.data).status).color"
        class="text-white"
        small
      >
       {{JSON.parse(result.data).status}}
      </v-chip>
        </v-card-subtitle>
        <v-card-text>
          {{JSON.parse(result.data).message}}
        </v-card-text>
      </v-card>
      </v-timeline-item>
    </v-timeline>

    <div class="d-flex justify-content-center" v-if="empty">
      No updates found
    </div>
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
import { integer } from 'vee-validate/dist/rules';
import Report from "../apis/Report"
  export default {
    data:()=>({
      search:'',
      results:[],
      max:7,
      isSearching:false,
      empty:null
    }),
    methods:{
       closeDialog(){
        this.isSearching = false;
        this.results = [];
        this.empty = null;
        this.search = '';
        this.$store.commit("SET_TRACK_REPORT_DIALOG",false)
      },
      fetchReportStatus(){
       this.isSearching = true;
       this.empty = null;
       Report.getStatus(this.search).then((res=>{
         this.results = res.data.reports;
         console.log(this.results.length)
        if(this.results.length < 1){
          this.empty = true;
        }
        this.isSearching = false;
      }));
      },


checkStatus(status){
if(status.toLowerCase() == "approved"){
  return {
    color:'#56a95b',
    value:'Approved',
  }
}else if(status.toLowerCase() == "reviewing"){
 return {
    color:'#fe9600',
    value:'Reviewing',
  }
  }else{
    return {
    color:'#1976d2',
    value:'Pending',
  }
  }
},
    }
  }
</script>
