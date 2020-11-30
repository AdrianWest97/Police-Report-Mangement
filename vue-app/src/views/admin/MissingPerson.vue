<template>
   <v-row>
       <v-col cols="12">
            <v-sheet  v-if="firstLoad" :loading="loading">
        <v-skeleton-loader class="mx-auto" type="table"></v-skeleton-loader>
    </v-sheet>
     <v-card flat :loading="reload">
    <v-card-text>
        <v-data-table
    :headers="computedHeaders"
    :items="missing"
    v-show="!firstLoad"

  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Missing People</v-toolbar-title>


        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
              <v-spacer></v-spacer>
    <v-btn class="mx-2" @click="addNew()"  small outlined>
      <v-icon dark>mdi-plus</v-icon>
      Create new
    </v-btn>

            <v-dialog v-model="dialogDelete" max-width="500px" style="z-index:9999" overlay-color="#8c95a6">
          <v-card loading="true">
            <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-toolbar>
    </template>
        <template v-slot:item.actions="{ item }">
                <v-icon
                class="mr-2"
                 medium
                @click="editItem(item)"
                >
            mdi-pencil-outline
               </v-icon>

       <v-icon
        medium
       :disabled="item.status == 2"
      @click="deleteItem(item)"
      >
        mdi-delete-outline
      </v-icon>
         </template>




        <template v-slot:item.parish="{ item }">
            {{item.address.parish}}
         </template>

           <template v-slot:item.photo="{ item }">
                <v-avatar
                color="grey"
                tile
                size="45"
                class="m-2"

                >
              <img class="rounded"  :src="loadImage(item.image.path)" :alt="item.fname"/>
              </v-avatar>

         </template>

          <template v-slot:item.parish="{ item }">
            {{item.address.parish}}
         </template>



        <template v-slot:item.created_at="{ item }">
             {{ item.created_at| moment("ddd, MMM D YYYY") }}
         </template>



    <template v-slot:no-data>
      <v-btn
        color="primary"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
    </v-card-text>
     </v-card>
       </v-col>
       <report-missing/>
       <snack-bar/>
   </v-row>
</template>

<script>
import MissingPerson from '../../apis/MissingPerson';
import ReportMissing from '../../components/ReportMissing.vue';
import SnackBar from '../../components/SnackBar.vue';
export default {
  components: { ReportMissing, SnackBar },
data:()=>({
  loading:false,
  reload:false,
      firstLoad:true,
      dialog: false,
      dialogDelete: false,
      headers: [
        {text: 'Date Created',align: 'start',value: 'created_at'},
        { text: 'id', value: 'id', align:'d-none' },
        { text: 'Photo', value: 'photo' },
        { text: 'First Name', value: 'fname' },
        { text: 'Last Name', value: 'lname' },
        { text: 'Age', value: 'age' },
        { text: 'Gender', value: 'gender' },
        { text: 'Parish', value: 'parish' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
}),

methods:{
 //loadTable
      loadTable(){
          this.$store.dispatch('fetchAllMissing');
          setTimeout(() => {
          if (this.firstLoad) this.firstLoad = false
          this.loading = false;
        });

 },
 addNew(){
   this.$store.commit('SET_MISSING_REPORT_DIALOG',{
     dialog:true,
     mode:'add',
     report:null
   })
 },

loadImage(image){
return 'http://localhost:8000/storage/images/'+image
},

 editItem (item) {
      this.$store.commit('SET_MISSING_REPORT_DIALOG',{
     dialog:true,
     mode:'edit',
     report:item
   })
      },

     deleteItem (item) {
        this.editedIndex = this.missing.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

        deleteItemConfirm () {
          this.reload = true;
        var temp = this.editedIndex;
        MissingPerson.deleteReport(this.missing[this.editedIndex].id)
        .then((res)=>{
                this.missing.splice(temp,1)
                 this.reload = false;
                this.$store.commit('SET_SNACK_BAR',{
                    visible:true,
                    content:"Item deleted",
                    timeout:2000
                })
        })
        this.closeDelete()
      },
      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
},
       computed: {
       missing(){
         return this.$store.getters.getAllMissing;
       },
       computedHeaders () {
      return this.headers.filter(headers => headers.text != "id");
   }
 },
 created(){
   this.loadTable();
 }
}
</script>

<style>

</style>
