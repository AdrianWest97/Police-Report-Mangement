<template>
  <div>
    <navigation></navigation>
   <v-container>
    <v-row class="my-16">


      <v-col cols="12" class="my-5">
       <h2>Help us locate these missing persons</h2><br/>
       <p>
       Know of someone missing? Call 119 or <a href="" @click.prevent="addNew()"><u>create a report online</u></a></p>
      </v-col>

         <v-sheet  v-if="firstLoad" :loading="loading">
        <v-skeleton-loader class="mx-auto" type="table"></v-skeleton-loader>
         </v-sheet>

      <v-col cols="12">
      <v-card elevation="0" outlined>
  <v-data-table
    :headers="computedHeaders"
    item-key="name"
    :items-per-page="5"
    class="elevation-0"
    :search="search"
   :items="missingList"
    v-show="!firstLoad"
  >
       <template v-slot:item.image="{ item }">
               <v-img
               :src="loadPath(item.image.path)"
               contain
                :aspect-ratio=".5"
                width="200"
               ></v-img>
        </template>

        <template v-slot:top>
             <v-toolbar
                flat
              >
        <p>{{missingList.length}} found</p>
          <v-spacer></v-spacer>
<div class="search-box">
        <v-text-field
          v-model="search"
          label="Search...."
          class="my-16"
        >
                <template slot="append">
           <v-icon medium> mdi-magnify </v-icon>
      </template>
        </v-text-field>
</div>
             </v-toolbar>
      </template>
                  <template v-slot:item.parish="{ item }">
                    {{item.address.parish}}
                  </template>

            <template v-slot:item.details="{ item }">
                              <!--additional details-->
                <v-list-item>
                <v-list-item-content>
                  <v-list-item-title><h2 class="text-bold">{{item.headline ? item.headline : ""}}</h2></v-list-item-title>
                </v-list-item-content>
              </v-list-item>

               <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>Name:</v-list-item-subtitle>
                  <v-list-item-title>{{item.name}}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Last seen:</v-list-item-title>
                  <v-list-item-subtitle >{{item.last_seen_date | moment("ddd, MMM D YYYY")}}</v-list-item-subtitle >
                  <v-list-item-subtitle   style="white-space:normal;">{{item.last_seen_details}}</v-list-item-subtitle >
                </v-list-item-content>
              </v-list-item>
      <v-container dense>
        <v-row dense>
          <v-col cols="10">
        <v-text-field
        label="Know anything? leave a anonymous tip"
        hide-details
        solo
        v-model="reply[missingList.indexOf(item)]"
      >
        <template slot="append">
           <v-icon @click="sendTip(reply[missingList.indexOf(item)])">mdi-send</v-icon>
      </template>
      </v-text-field>
          </v-col>
        </v-row>
      </v-container >
     </template>

  <template v-slot:no-data>
  <no-data :btnClickHandler="fetchReports">

  </no-data>

    </template>
  </v-data-table>
      </v-card>

      </v-col>
    </v-row>
   </v-container>
   <report-missing></report-missing>
   <snack-bar></snack-bar>
  </div>
</template>

<script>
import Navigation from '../components/Navigation.vue'
import NoData from '../components/NoData.vue';
import ReportMissing from '../components/ReportMissing.vue';
import SnackBar from '../components/SnackBar.vue';
export default {
  data:()=>({
     search: '',
     reply:[],
     firstLoad:true,
     loading:true,
    headers:[
       { text: 'Image', value: 'image',width:'1%' },
       { text: 'Details', value: 'details' },
       { text: 'Name', value: 'name',align:' d-none' },
       { text: 'Parish', value: 'parish', sortable:true},
    ]
  }),
components:{
Navigation,
NoData,
ReportMissing,
SnackBar
},
computed:{
  computedHeaders(){
       return this.headers;
  },
    missingList(){
    return this.$store.state.MissingPersons.filter((report)=> report.status == 2);
  }
},
methods:{
    fetchReports(){
      if(this.missingList != null){
        setTimeout(() => {
          if (this.firstLoad) this.firstLoad = false
          this.loading = false;
        }, 1000);

      }
    },
  loadPath(image){
    return `${process.env.APP_URL}/storage/${image}`
  },
   addNew(){
   this.$store.commit('SET_MISSING_REPORT_DIALOG',{
     dialog:true,
     mode:'add',
     report:null
   })
 },
   sendTip(rep){
               this.reply ="";
              this.$store.commit('SET_SNACK_BAR',{
                    visible:true,
                    content:"Reponse noted",
                    timeout:2000
                })
  },
},
created(){
  this.$store.dispatch('fetchAllMissing');
  this.fetchReports();
}

}
</script>

<style scoped lang="scss">
.v-data-table
  /deep/
  tbody
  /deep/
  tr:hover:not(.v-data-table__expanded__content) {
  background: #ffffff !important;
}
</style>
