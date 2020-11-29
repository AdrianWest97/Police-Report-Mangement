<template>
     <v-row>
       {{$store.state.num}}
       <v-col cols="12">
             <v-sheet  v-if="firstLoad" :loading="loading">
        <v-skeleton-loader class="mx-auto" type="table"></v-skeleton-loader>
    </v-sheet>
     <v-card flat>
    <v-card-text>
  <v-data-table
    :headers="computedHeaders"
    :items="pending"
    v-show="!firstLoad"

  >

    <template v-slot:item.status="{ item }">
      <v-chip
        :color="checkStatus(item.status).color"
        class="text-white"
      >
       {{checkStatus(item.status).value}}
      </v-chip>
    </template>

    <template v-slot:item.email="{ item }">
       {{item.user.email}}
    </template>

        <template v-slot:item.type="{ item }">
       {{item.type.type}}
    </template>

       <template v-slot:item.name="{ item }">
       {{item.user.name}}
    </template>

    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>REPORTS</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
      </v-toolbar>
    </template>


      <template v-slot:item.date="{ item }">
               {{ item.date | moment("ddd, MMM D YYYY") }}
        </template>


        <template v-slot:item.actions="{ item }">
                <v-icon
                class="mr-2"
                 medium
                 @click="respond(item)"
                >
             mdi-comment-eye-outline
               </v-icon>
    </template>


    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="fetchReports()"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
    </v-card-text>
  </v-card>
  <respond-modal></respond-modal>
    </v-col>
     </v-row>
</template>

<script>
import RespondModal from '../../components/admin/RespondModal';
import Vue from 'vue';
import { mapGetters } from 'vuex';
export default {
  data:()=> ({
        loading:true,
        firstLoad:true,
        // pending:[],
        headers: [
        {text: 'Date',align: 'start',value: 'date'},
        { text: 'id', value: 'id', align:'d-none' },
        { text: 'Reference #',  value: 'reference_number' },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Phone', value: 'phone' },
        { text: 'Status', value: 'status' },
        { text: 'Type', value: 'type' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
  }),


  methods:{
    fetchReports(){
      if(this.pending != null){
        setTimeout(() => {
          if (this.firstLoad) this.firstLoad = false
          this.loading = false;
        }, 1000);

      }

    },
 checkStatus(status){
if(status == 2){
  return {
    color:'#56a95b',
    value:'Approved',
  }
  }else if(status == 1){
 return {
    color:'#fe9600',
    value:'Reviewing',
  }
  }else{
     return {
    color:'#f44236',
    value:'Pending',
  }
  }
},

respond(item){
  this.$store.commit('SET_RESPOND_DIALOG',{
    visible:true,
    report:item
  })
}
},


computed:{
  ...mapGetters['getAllReports'],
  computedHeaders(){
    return this.headers.filter(header=> header.text != 'id')
  },
  pending(){
    return this.$store.state.AllReports;
  }
},

created(){
 this.$store.dispatch('fetchAllReports');
 this.fetchReports();
},


components:{
RespondModal
},
}
</script>

<style>

</style>
