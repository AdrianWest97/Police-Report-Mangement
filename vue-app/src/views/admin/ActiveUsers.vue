<template>
   <v-row>
       <v-col cols="12">
             <v-sheet v-if="firstLoad" :loading="loading">
        <v-skeleton-loader class="mx-auto" type="table"></v-skeleton-loader>
    </v-sheet>
     <v-card flat>
    <v-card-text>
      <v-data-table
    :headers="computedHeaders"
    :items="users"
    v-show="!firstLoad"
  >
          <template v-slot:item.actions="{ item }">
                <v-icon
                class="mr-2"
                 medium
                 @click="respond(item)"
                >
                    mdi-delete-outline
               </v-icon>
    </template>
          <template v-slot:item.created_at="{ item }">
               {{ item.created_at | moment("ddd, MMM D YYYY") }}
        </template>


    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="getUsers()"
      >
        Refresh
      </v-btn>
    </template>
      </v-data-table>
    </v-card-text>
     </v-card>
       </v-col>
   </v-row>
</template>

<script>
import Report from '../../apis/Report'
export default {
data:()=>({
  loading:true,
  firstLoad:true,
  users:[],
      headers: [
        {text: 'Created',align: 'start',sortable: false,value: 'created_at'},
        {text: 'id',sortable: false,value: 'id'},
        { text: 'Name', value: 'name', align:'d-none' },
        { text: 'Gender', value: 'gender', align:'d-none' },
        { text: 'Email',  value: 'email' },
        { text: 'TRN', value: 'trn' },
        { text: 'Phone #', value: 'phoneno' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
}),

methods:{
  getUsers(){
    Report.getActiveUsers()
    .then((res=> {
      this.users = res.data.users
       setTimeout(() => {
          if (this.firstLoad) this.firstLoad = false
          this.loading = false;
        }, 1000);
    }))
  }
},
computed:{
    computedHeaders(){
    return this.headers.filter(header=> header.text != 'id')
  }
},
created(){
this.getUsers();
},
}
</script>

<style>

</style>
