<template>
<div>
  <v-row justify="center">
    <v-dialog v-model="reportDialog.dialog"
       scrollable max-width="750px"
       style="z-index:9999"
       overlay-color="#8c95a6"
       persistent
       >
      <v-card :loading="loading">
        <v-card-title>
          <span class="text-small text-bolder">Quick Report</span>
          <v-spacer></v-spacer>
  <v-switch v-model="form.anonymous">
      <template v-slot:label>
        Anonymous report?
         <v-icon small>mdi-help-circle</v-icon>
      </template>
    </v-switch>
        </v-card-title>
        <v-divider></v-divider>
                <v-card-text>
          <validation-observer
            ref="observer"
            v-slot="{ invalid }"
          >
          <v-form ref="form" @submit.prevent="submit">
          <v-container>
            <v-row>
                  <v-col cols="12">
                <v-card flat>
                  <v-card-title>
                          <v-row>
                     <v-col
                cols="12"
                sm="6"
                md="6"
              >
               <validation-provider name="type" rules="required">
											<v-select
												:items="reportTypes.map(type => type.type)"
												v-model="form.type"
												label="Select Report Type"
												slot-scope="{ errors }"
												:error-messages="errors"
												required
											></v-select>
										</validation-provider>
              </v-col>
                  <v-col
                cols="12"
                sm="6"
                md="6"
              >
      <validation-provider name="date" rules="required">
      <v-menu
        v-model="menu2"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="form.date"
            label="Date of inccident"
            prepend-icon="mdi-calendar"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="form.date"
          @input="menu2 = false"
        ></v-date-picker>
      </v-menu>
    </validation-provider>
              </v-col>

                  </v-row>
                  </v-card-title>
                </v-card>
              </v-col>


              <v-col cols="12">
                <v-card flat style="margin:0 !important">
                  <v-card-subtitle>Location</v-card-subtitle>
                  <v-card-title>
                    <v-row>
                      <v-col
                      cols="12"
                      sm="6"
                      md="6">
                      <validation-provider name="type" rules="required">
											<v-select
												:items="parishes"
												v-model="form.parish"
												label="Select Parish"
												slot-scope="{ errors }"
												:error-messages="errors"
												required
											></v-select>
										</validation-provider>
                      </v-col>
                          <v-col
                       cols="12"
                       sm="6"
                       md="6"
                        >
                    	<validation-provider name="city" rules="required">
											<v-text-field
												label="City"
												v-model="form.city"

												hide-details="auto"
												slot-scope="{ errors }"
												:error-messages="errors"
												required
											></v-text-field>
										</validation-provider>
                          </v-col>
                              <v-col
                       cols="12"
                       sm="6"
                       md="12"
                        >
                    	<validation-provider name="street" rules="required">
											<v-text-field
												label="Street Line"
												v-model="form.street"
												hide-details="auto"
												slot-scope="{ errors }"
												:error-messages="errors"
												required
											></v-text-field>
										</validation-provider>
                          </v-col>
                    </v-row>
                  </v-card-title>
                </v-card>
              </v-col>

              <v-col cols="12">
                <v-card flat>
                  <v-card-subtitle>Description of inciddent</v-card-subtitle>
                  <v-card-title>
                    <v-row>
                      <v-col
                      cols="12">
                <validation-provider name="details" rules="required">
                   <v-textarea
                    counter
                    rows="2"
                     row-height="50"
                    auto-grow
                    label="Details"
                   filled
                    class="mt-1"
                    v-model="form.details"
                    slot-scope="{ errors }"
										:error-messages="errors"
										required
                  ></v-textarea>
              	</validation-provider>
                      </v-col>
                      <v-col cols="12">
                   <v-checkbox
                  v-model="form.hasWitness"
                  :label="'Were there any witneeses to the inccident? '"
                  class="m-0 p-0"
                ></v-checkbox>

                      <ul v-show="form.hasWitness && temp_witnesses.length > 0" class="list-group m-0 mb-2 p-0" v-for="(witness,index) in temp_witnesses" :key="index">
                          <transition name="fade">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                         <span class="text-bold">{{witness.name.toUpperCase()}}</span>
                                                    <span class="text-bold">#{{witness.phone}}</span>

                                                          <v-icon
                                                          small
                                                          class="mr-2"
                                                          slot="a"
                                                          @click="deleteWitness(index)"
                                                          >
                                                      mdi-trash-can
                                                        </v-icon>


                                                </li>
                                                </transition>
                                            </ul>
                <div v-if="form.hasWitness"  class="d-flex flex-row w-100">
                   	<validation-provider name="Name">
											<v-text-field
												label="Full name"
                        v-model="temp_name"
												hide-details="auto"
                        class="m-1"
											></v-text-field>
										</validation-provider>
                     	<validation-provider name="Phone no.">
											<v-text-field
												label="Phone #"
												hide-details="auto"
                        v-model="temp_phone"
                        class="m-1"

											></v-text-field>
										</validation-provider>
                    <v-btn  color="primary" @click="addWitness()">Add</v-btn>
                </div>

              </v-col>
               <v-col
                 cols="12">
                <v-textarea
                    counter
                    rows="2"
                     row-height="50"
                    auto-grow
                    filled
                    label="Additional information"
                    class="mt-1"
                    v-model="form.additional"
                  ></v-textarea>
                      </v-col>
                  	<validation-provider class="mx-4" name="Accepted terms" rules="required">
                    <v-checkbox required v-model="form.accepted_terms">
                    <template v-slot:label>
                         By clicking submit, you certify that all the given information is true and correct.
                        </template>
                      </v-checkbox>
                  	</validation-provider>
                    </v-row>
                  </v-card-title>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
           @click="closeDialog()"
          >
            Cancel
          </v-btn>
          <v-btn
            color="primary"
           :loading="loading"
            type="submit"
            :disabled="invalid"
            @click.prevent="submit()"
          >
          Submit Report
          </v-btn>
        </v-card-actions>
        </v-form>
   </validation-observer>
  </v-card-text>
      </v-card>
    </v-dialog>
  </v-row>
  <success-modal/>
</div>
</template>

<script>
import Report from '../apis/Report'
import Api from '../apis/Api';
import { required, max } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import { mapGetters} from 'vuex'
import SuccessModal from '../components/successModal.vue';


setInteractionMode('eager')
extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })

  extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
  })


  export default {
   data:()=>{
     return{
      loading: false,
      menu2:false,
       form:{
      date: new Date().toISOString().substr(0, 10),
        now:false,
        parish:'',
        city:'',
        street:'',
        type:'',
        details:'',
        accepted_terms:null,
        hasWitness:false,
        witnesses:[],
        additional:'',
        anonymous:false
       },
      temp_witnesses:[],
       temp_name:'',
       temp_phone:'',
      	menu: false,

     }
   },
   computed:{
     ...mapGetters(['reportTypes','parishes','reportDialog']),
   },
   methods:{
     deleteWitness(index){
       this.form.witnesses.splice(index,1);
     },
     addWitness(){
       if(this.temp_name != "" && this.temp_phone !=""){
         this.temp_witnesses = [...this.temp_witnesses,{
           name:this.temp_name,
           phone:this.temp_phone
         }]
         this.temp_name ="";
         this.temp_phone ="";
       }
     },
     closeDialog(){
      this.loading = false;
      this.form.anonymous = false;
      this.$refs.form.reset()
       this.temp_witnesses = [];
       this.$store.commit('SET_REPORT_DIALOG',false)
     },
     submit(){
      this.loading = true;
       this.$refs.observer.validate();
       if(this.temp_witnesses.length > 0){
         this.form.witnesses = JSON.stringify(this.temp_witnesses)
       }
        Api().post("/create",this.form)
        .then((response) => {
          this.loading = false;
          //close form
          //show success
          this.$store.commit('SET_SUCCESS_DIALOG',{
          content:response.data.reference_number,
          message:'Your report has been submmited for review and approval. Save this reference number to track your report.',
          visible:true,
          title:'Success'
        });
          this.closeDialog();
        })
     },
   },
   created(){
     Report.reportTypes().then(res => this.$store.commit('REPORT_TYPES',res.data.types));
   },
    components:{
     ValidationProvider,
      ValidationObserver,
        SuccessModal,
  }
  }
</script>
