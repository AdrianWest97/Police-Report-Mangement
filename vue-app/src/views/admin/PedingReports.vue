<template>
     <v-row>
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
        small
      >
       {{checkStatus(item.status).value}}
      </v-chip>
    </template>

    <template v-slot:item.email="{ item }">
       {{item.user.email == "guest@prms.com" ? "Anonymous" : item.user.email}}
    </template>

        <template v-slot:item.type="{ item }">
       {{item.type.type}}
    </template>

       <template v-slot:item.name="{ item }">
             {{item.user.email == "guest@prms.com" ? "Anonymous" : item.user.name}}

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

             <template v-slot:item.parish="{ item }">
               {{ item.address.parish }}
        </template>

   <template v-slot:item.reference_number="{ item }">
               {{ item.reference_number.toUpperCase() }}
        </template>


   <template v-slot:item.witnesses="{ item }">
               {{ item.witnesses.length }}
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
<no-data :btnClickHandler="fetchReports"></no-data>
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
import { mapGetters } from 'vuex';
import NoData from '../../components/NoData.vue';
export default {
  data:()=> ({
        loading:true,
        firstLoad:true,
        // pending:[],
        headers: [
        {text: 'Date',align: 'start',value: 'date'},
        { text: 'id', value: 'id', align:'d-none' },
        { text: 'Reference #',  value: 'reference_number' },
        {text: 'Witnesses', value:'witnesses',align:'d-none'},
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Phone', value: 'phone' },
        { text: 'Parish', value: 'parish' },
        { text: 'Status', value: 'status' },
        { text: 'Type', value: 'type' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
  }),


  methods:{
    fetchReports(){
       this.$store.dispatch('fetchAllReports');

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
    color:'success',
    value:'Approved',
  }
  }else if(status == 1){
 return {
    color:'warning',
    value:'Reviewing',
  }
  }else{
     return {
    color:'info',
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
 this.fetchReports();
},


components:{
RespondModal,
NoData
},
}
</script>

<style>

</style>
