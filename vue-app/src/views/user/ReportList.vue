<template>
    <v-container>
        <v-row>
           <v-col cols="12" >
    <v-sheet  v-if="firstLoad" :loading="loading">
        <v-skeleton-loader class="mx-auto" type="table"></v-skeleton-loader>
    </v-sheet>
  <v-card outlined>
    <v-card-text>
  <v-data-table
    v-show="!firstLoad"
    :headers="computedHeaders"
    :items="reports"
  >

    <template v-slot:item.status="{ item }">
      <v-chip
        :color="checkStatus(item.status).color"
        class="text-white"
      >
       {{checkStatus(item.status).value}}
      </v-chip>
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

        <v-dialog v-model="dialogDelete" max-width="500px" style="z-index:9999" overlay-color="#8c95a6">
          <v-card>
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
                medium
                class="mr-2"
                 @click="editItem(item)"
                 :disabled="item.status == 2"
                >
             mdi-pencil-outline
               </v-icon>

      <v-icon
        medium
        @click="deleteItem(item)"
       :disabled="item.status == 2"

      >
        mdi-delete-outline
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
  </v-col>
  <snack-bar/>
  <edit-report/>
  </v-row>
    </v-container>
</template>

<script>
import Report from '../../apis/Report';
import EditReport from '../../components/EditReport.vue';
import SnackBar from '../../components/SnackBar.vue';
export default {
  data () {
      return {
      loading:true,
      firstLoad:true,
      dialog: false,
      dialogDelete: false,
     headers: [
        {text: 'Date',align: 'start',sortable: false,value: 'date'},
        { text: 'id', value: 'id', align:'d-none' },
        { text: 'Reference #',  value: 'reference_number' },
        { text: 'Type', value: 'type' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      reports: [],
      editedIndex: -1,
      editedItem: {
        date: '',
        reference_number: "",
        type: "",
        status: "",
        id:""
      },
      defaultItem: {
        date: '',
        reference_number: "",
        type: "",
        status: "",
        id:0
      },
      }
    },
  methods:{
    fetchReports(){
      return Report.reports()
      .then((res)=>{
             res.data.reports.forEach(report =>{
                this.reports = [...this.reports, {
                id:report.id,
                date: report.date,
                reference_number: report.reference_number.toUpperCase(),
                type: report.type.type,
                status: report.status,
                }]
            });
            setTimeout(() => {
          if (this.firstLoad) this.firstLoad = false
          this.loading = false;
        }, 1000);
      });

    },
      editItem (item) {
        Report.getEdit(item.id)
        .then((res)=> {
        this.$store.commit('SET_EDIT_REPORT_DIALOG',{
         visible:true,
         report:res.data.report
      })
        });
      },

     deleteItem (item) {
        this.editedIndex = this.reports.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

       async deleteItemConfirm () {
       await Report.delete(this.reports[this.editedIndex].id)
        .then((res)=>{
                this.reports.splice(this.editedIndex,1)
                this.$store.commit('SET_SNACK_BAR',{
                    visible:true,
                    content:"Item deleted",
                    timeout:2000
                })
        })
        this.closeDelete()
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

           closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
  },

    watch: {
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },
      computed: {
     computedHeaders () {
      return this.headers.filter(headers => headers.text != "id");
   }
      },
 created(){
   this.fetchReports();
 },
 components:{
    SnackBar,
  EditReport
}
}
</script>

<style>

</style>
