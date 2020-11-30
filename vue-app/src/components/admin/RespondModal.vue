<template>
  <v-row justify="center">
    <v-dialog
      v-model="getRespondDialog.visible"
       scrollable
       max-width="800px"
       overlay-color="#8c95a6"
       persistent
       close-delay="1000"
       retain-focus
    >
        <v-sheet color="light" v-if="getRespondDialog.report == null">
        <v-skeleton-loader class="mx-auto"   type="article"
         ></v-skeleton-loader>
        </v-sheet>
      <v-card v-else :loading="loading">
        <v-card-title class="headline"> Respond </v-card-title>
        <v-card-subtitle> See report details and update status</v-card-subtitle>
        <v-divider></v-divider>
       <v-card-text>
        <v-card flat>
         <v-card-subtitle>Report details</v-card-subtitle>
        <v-card-text>
                    <!--type-->
    <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title>Type</v-list-item-title>
        <v-list-item-subtitle>{{ getRespondDialog.report.type.type.toUpperCase()}}</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
            <!--date-->
    <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title>Date</v-list-item-title>
        <v-list-item-subtitle>{{ getRespondDialog.report.updated_at | moment("ddd, MMM D YYYY") }}</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

             <!--Address-->
         <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title>Location</v-list-item-title>
        <v-list-item-subtitle>{{getRespondDialog.report.address.parish}}</v-list-item-subtitle>
        <v-list-item-subtitle>{{getRespondDialog.report.address.city}}</v-list-item-subtitle>
        <v-list-item-subtitle>{{getRespondDialog.report.address.street}}</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

          <!--details-->
    <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title>Details</v-list-item-title>
        <v-list-item-subtitle  style="white-space:normal;"><truncate clamp="Read More" less="Show Less" :text="getRespondDialog.report.details"></truncate></v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

              <!--witness-->
    <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title>Witnesses</v-list-item-title>
        <v-list-item-subtitle>
                  <ul v-show="getRespondDialog.report.witnesses.length > 0" class="list-group m-0 mb-2 p-0" v-for="(witness,index) in getRespondDialog.report.witnesses" :key="index">
                          <transition name="fade">
                            <li class="list-group-item d-flex border-0 p-0 justify-content-between align-items-center">
                                         <span class="text-bold">{{witness.name.toUpperCase()}}</span>
                                                    <span class="text-bold">#{{witness.phone}}</span>
                                                </li>
                                                </transition>
                                            </ul>
        </v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

              <!--additional details-->
    <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title>Additional details</v-list-item-title>
        <v-list-item-subtitle  style="white-space:normal;"><truncate clamp="Read More" less="Show Less" :text="getRespondDialog.report.additional"></truncate></v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

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

    <v-btn
      color="primary"
      class="float-right"
      depressed
      type="submit"
      :loading="loading"
      :disabled="invalid"
    >
    Send Update
    </v-btn>
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
  </v-row>
</template>

<script>
import { mapGetters } from 'vuex';
import truncate from 'vue-truncate-collapsed';
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import Report from '../../apis/Report';


  export default {
  data:()=> ({
       loading: false,
        form:{
        message:'',
        status:null,
        id:null,
        userId:''
        }
    }),
    components:{
      truncate,
       ValidationProvider,
      ValidationObserver,
    },
    computed:{
      ...mapGetters(['getRespondDialog']),
    },
    methods:{
       closeDialog(){
        this.$refs.form.reset();
        this.loading = false;
        this.$store.commit("SET_RESPOND_DIALOG",{
          visible:false,
          report:null
        })

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
               this.form.id=this.getRespondDialog.report.id;
              this.form.userId = this.getRespondDialog.report.user.id;
              console.log(this.form)
              Report.updateStatus(this.form).
              then((res)=>{
                this.loading = false;
                var item = this.$store.state.AllReports.filter(report=> report.id == this.form.id);
                item[0].status = this.form.status;
                this.$store.commit('EDIT_REPORT',item);
                this.closeDialog();

              })
      }
    },

  }
</script>
