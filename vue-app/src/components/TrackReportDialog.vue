<template>
  <v-row justify="center">
    <v-dialog
      v-model="$store.state.TrackReportDialog.visible"
       scrollable
        max-width="600px"
       overlay-color="#8c95a6"
       keydown
      transition="slide-y-transition"


    >
      <v-card
       :loading="isSearching">
        <v-card-title class="headline">

        <v-text-field
        v-model="query"
        label="Enter 6 digit reference number"
        hide-details
        rounded
        :maxlength="max"
        @input="query = query.toUpperCase();"
        v-on:keyup.enter="query != '' ? fetchReportStatus() : ''"

      >
        <template slot="append">
           <v-icon v-show="query"  @click="fetchReportStatus()" medium> mdi-magnify </v-icon>
      </template>
      </v-text-field>
        </v-card-title>
        <v-card-text>
       <v-timeline v-show="results.length > 0" dense class="my-4">
      <v-timeline-item
        v-for="result in results"
        :key="result.id"
        small
        fill-dot
       class="white--text"
       :color="checkStatus(JSON.parse(result.data).status).type"
      >
              <v-alert
               promininet
               :color="checkStatus(JSON.parse(result.data).status).type"
               text
              dense
              dark
        border="left"

             >
        <div class="title text-dark">{{result.created_at | moment("ddd, MMM D YYYY")}}</div>
         <v-chip
        :color="checkStatus(JSON.parse(result.data).status).type"
        class="text-white"
        small
      >
       {{JSON.parse(result.data).status}}
      </v-chip>
      <div class="text-dark my-2">
        {{JSON.parse(result.data).message}}
      </div>
      </v-alert>
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
import Report from "../apis/Report"
  export default {
    data:()=>({
      query:'',
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
        this.query = '';
        this.$store.commit("SET_TRACK_REPORT_DIALOG",false)
      },
      fetchReportStatus(){
       this.isSearching = true;
       this.empty = null;
       Report.getStatus(this.query).then((res=>{
         this.results = Object.values(res.data.reports);
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
    type:'success'
  }
}else if(status.toLowerCase() == "reviewing"){
 return {
    value:'Reviewing',
    type:'warning'
  }
  }else{
    return {
    value:'Pending',
    type:'info'
  }
  }
},
    }
  }
</script>


<style scoped>

</style>
