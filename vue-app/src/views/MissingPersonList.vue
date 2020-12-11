<template>
  <div>
    <navigation></navigation>
   <v-container>
    <v-row class="my-16">


      <v-col cols="12" class="my-5">
       <h1> Missing persons</h1>
       <small>Please help us locate these missing persons. Call 119 or create a report online</small>
      </v-col>

         <v-sheet  v-if="firstLoad" :loading="loading">
        <v-skeleton-loader class="mx-auto" type="table"></v-skeleton-loader>
         </v-sheet>

      <v-col cols="12">
      <v-card elevation="0" outlined>
  <v-data-table
    :headers="computedHeaders"
    :items="getAllMissing"
    item-key="name"
    :items-per-page="5"
    class="elevation-0"
    :search="search"
    v-show="!firstLoad && getAllMissing"
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
        <p>{{getAllMissing.length}} found</p>
          <v-spacer></v-spacer>

        <v-text-field
          v-model="search"
          label="Search...."
          dense
        >
                <template slot="append">
           <v-icon medium> mdi-magnify </v-icon>
      </template>
        </v-text-field>
             </v-toolbar>
      </template>
                  <template v-slot:item.parish="{ item }">
                    {{item.address.parish}}
                  </template>

            <template v-slot:item.details="{ item }">
                              <!--additional details-->
                <v-list-item>
                <v-list-item-content>
                  <v-list-item-title><span class="text-bold">{{fullname(item.fname,item.lname)}}</span></v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>{{item.created_at | moment("ddd, MMM D YYYY")}}</v-list-item-title>
                  <v-list-item-subtitle  style="white-space:normal;">{{item.last_seen_details}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>

      <v-list three-line>
      <v-list-item>
      <v-list-item-content>
        <v-list-item-title>Attributes</v-list-item-title>
        <v-list-item-subtitle>Height: {{JSON.parse(item.attributes).height}}</v-list-item-subtitle>
        <v-list-item-subtitle>Hair color: {{JSON.parse(item.attributes).eye_color}}</v-list-item-subtitle>
        <v-list-item-subtitle>Eye color: {{JSON.parse(item.attributes).hair_color}}</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
      </v-list>
      <v-container class="border" dense>
        <v-row dense>
          <v-col cols="10">
                <v-text-field
      filled
      placeholder="Know anthing?"
      v-model="reply"
      >
      </v-text-field>
          </v-col>
          <v-col>
                  <v-btn text>Submit</v-btn>

          </v-col>
        </v-row>
      </v-container>
     </template>
  </v-data-table>
      </v-card>

        <!--no data found--->

<v-sheet v-show="getAllMissing == null">
<h3>No Data found</h3>
</v-sheet>
      </v-col>
    </v-row>
   </v-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Navigation from '../components/Navigation.vue'
export default {
  data:()=>({
     search: '',
     reply:'',
     firstLoad:true,
     loading:false,
    headers:[
       { text: 'Image', value: 'image',width:'1%' },
       { text: 'Details', value: 'details' },
       { text: 'First name', value: 'fname',align: ' d-none' },
       { text: 'Last name', value: 'lname',align: ' d-none' },
       { text: 'Parish', value: 'parish', sortable:true},
    ]
  }),
components:{
  Navigation
},
computed:{
  ...mapGetters(['getAllMissing']),
  computedHeaders(){
       return this.headers;
  }
},
methods:{
  fullname(fname,lname){
    return fname.charAt(0).toUpperCase() + fname.slice(1) +" "+lname.charAt(0).toUpperCase() + lname.slice(1)
  },
  loadTable(){
      this.$store.dispatch('fetchAllMissing');
             setTimeout(() => {
          if (this.firstLoad) this.firstLoad = false
          this.loading = false;
        });
  },
  loadPath(image){
    return `${process.env.APP_URL}/storage/${image}`
  }
},
created(){
  this.loadTable();
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
