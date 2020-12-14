<template>
  <v-row justify="center">
    <v-dialog
      v-model="getMissingApproveDialog.visible"
       scrollable
       max-width="800px"
       overlay-color="#8c95a6"
       persistent
       close-delay="1000"
       retain-focus
      transition="slide-y-transition"

    >
        <v-sheet color="light" v-if="getMissingApproveDialog.report == null">
        <v-skeleton-loader class="mx-auto"   type="article"
         ></v-skeleton-loader>
        </v-sheet>
      <v-card v-else :loading="loading">
        <v-card-title class="headline"> Respond </v-card-title>
        <v-card-subtitle> See report details and update status</v-card-subtitle>
        <v-divider></v-divider>
       <v-card-text>
        <v-card flat>
         <v-card-subtitle>Report Details</v-card-subtitle>
        <v-card-text>
          <v-row>
            <v-col  col="6">
              <div class="border">
               <v-img
               :src="loadPath(getMissingApproveDialog.report.image.path)"
               contain
                max-width="200"
               ></v-img>
              </div>
            </v-col>
            <v-col cols="6">
                 <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title>Name:</v-list-item-title>
                  <v-list-item-subtitle  style="white-space:normal;">{{getMissingApproveDialog.report.name}}</v-list-item-subtitle>
                 <v-list-item-title>Last seen:</v-list-item-title>
               <v-list-item-subtitle  style="white-space:normal;">{{getMissingApproveDialog.report.last_seen_date  | moment("ddd, MMM D YYYY")}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title>Gender:</v-list-item-title>
                  <v-list-item-subtitle  style="white-space:normal;">{{getMissingApproveDialog.report.gender}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
               <v-list-item>
                <v-list-item-content three-line>
                  <v-list-item-title>Address:</v-list-item-title>
                  <v-list-item-subtitle  style="white-space:normal;">{{getMissingApproveDialog.report.address.parish}}</v-list-item-subtitle>
                  <v-list-item-subtitle  style="white-space:normal;">{{getMissingApproveDialog.report.address.city}}</v-list-item-subtitle>
                  <v-list-item-subtitle  style="white-space:normal;">{{getMissingApproveDialog.report.address.street}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-col>
             <v-col  cols="12">
               <v-list-item two-line>
               <v-list-item-content>
            <v-list-item-title>Details:</v-list-item-title>
             <v-list-item-subtitle  style="white-space:normal;">{{getMissingApproveDialog.report.last_seen_details}}</v-list-item-subtitle>
               </v-list-item-content>
             </v-list-item>
             </v-col>
          </v-row>
        </v-card-text>
        </v-card>
        <v-card class="my-5" flat>
          <v-card-subtitle>Actions</v-card-subtitle>
          <v-card-text>
              <validation-observer
                  ref="observer"
                  v-slot="{ invalid }"
                >
                    <v-form
                    @submit.prevent="updateReport"
                    ref="form"
                    >
                <v-row>
                <v-col cols="6">
                  <v-text-field
                  label="Add headline (optional)"
                  rounded
                  filled
                  v-model="form.headline"
                  >

                  </v-text-field>
                </v-col>
                      <v-col cols="6">
                  <validation-provider name="status" rules="required">
                    <v-select
                    filled
                    dense
                    rounded
                    slot-scope="{ errors }"
				           	:error-messages="errors"
                   v-model="form.status"
                  :items="['Pending','Reviewing','Approved']"
                  label="Update status"
                ></v-select>
                   </validation-provider>
              </v-col>
          <v-col cols="12">
        <validation-provider name="message" rules="required">
        <v-textarea
          name="input-7-4"
          label="Type a response"
          required
          filled
          v-model="form.message"
           slot-scope="{ errors }"
				  	:error-messages="errors"
        ></v-textarea>
        </validation-provider>
          </v-col>
<v-col cols="12">
      <v-btn
      color="primary"
      class="float-right"
      depressed
      type="submit"
      :loading="loading"
      :disabled="invalid"
    >
    Update
    </v-btn>
</v-col>
       </v-row>
      </v-form>
            </validation-observer>
        </v-card-text>
        </v-card>
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
    <snack-bar></snack-bar>
  </v-row>
</template>

<script>
import { mapGetters } from 'vuex';
import truncate from 'vue-truncate-collapsed';
import { ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import Report from '../apis/Report';
import SnackBar from './SnackBar.vue';
setInteractionMode('eager');

  export default {
  data:()=> ({
       loading: false,
        form:{
        message:'',
        status:null,
        id:null,
        headline:''
        }
    }),
    components:{
      truncate,
       ValidationProvider,
      ValidationObserver,
      SnackBar
    },
    computed:{
      ...mapGetters(['getMissingApproveDialog']),
    },
    methods:{
       closeDialog(){
        this.$refs.form.reset();
        this.loading = false;
        this.$store.commit("SET_MISSING_APPROVE_DIALOG",{
          visible:false,
          report:null
        })

      },

        loadPath(image){
         return `${process.env.APP_URL}/storage/${image}`
  },

      updateReport(){
               this.loading = true;
               this.$refs.observer.validate();
              if(this.form.status.toLowerCase() == 'pending'){
                this.form.status = 0;
               }else if(this.form.status.toLowerCase() == 'reviewing'){
                  this.form.status = 1;
               }else if(this.form.status.toLowerCase() == 'approved'){
                  this.form.status = 2;
               }else{
                 this.form.status = 0;
               }
             this.form.id=this.getMissingApproveDialog.report.id;
              Report.updateMissingStatus(this.form).
              then((res)=>{
                this.loading = false;
                var item = this.$store.state.MissingPersons.filter(report=> report.id == this.form.id);
                item[0].status = this.form.status;
                this.$store.commit('EDIT_MISSING_REPORT',item);
                    this.$store.commit('SET_SNACK_BAR',{
                    visible:true,
                    content:"Report status updated",
                    timeout:2000
                })
                this.closeDialog();
              })
      }
    },

  }
</script>
