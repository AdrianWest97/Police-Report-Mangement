<template>
<div>
  <v-row justify="center">
    <v-dialog v-model="reportDialog.dialog"
       scrollable max-width="800px"
       style="z-index:9999"
       overlay-color="#8c95a6"
       persistent
       >
      <v-card>
        <v-card-title>
          <span class="text-small text-bolder">New Police Report</span>
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
                     <v-menu
											v-model="menu"
											:close-on-content-click="false"
											transition="scale-transition"
											offset-y
										>
											<template v-slot:activator="{ on, attrs }">
												<v-text-field
													v-model="computedDateFormatted"
													label="When did this happen?"
													hint="MM/DD/YYYY format"
													persistent-hint
													prepend-icon="mdi-calendar"
													readonly

													v-bind="attrs"
													v-on="on"
												></v-text-field>
											</template>
											<v-date-picker
												v-model="form.date"
												no-title
												@input="menu = false"
											></v-date-picker>
										</v-menu>
              </v-col>

                  </v-row>
                  </v-card-title>
                </v-card>
              </v-col>


              <v-col cols="12">
                <v-card flat style="margin:0 !important">
                  <v-card-title>Location</v-card-title>
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
                  <v-card-title>Description of inciddent</v-card-title>
                  <v-card-title>
                    <v-row>
                      <v-col
                      cols="12">
                      	<validation-provider name="details" rules="required">
                   <v-textarea
                    :rules="textAreaRules"
                    counter
                    rows="2"
                     row-height="50"
                    auto-grow
                    label="Details"
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
                  :label="'Where there any witneeses to the inccident? '"
                  class="m-0 p-0"
                ></v-checkbox>

                      <ul v-show="form.hasWitness && form.witnesses.length > 0" class="list-group m-0 mb-2 p-0" v-for="(witness,index) in form.witnesses" :key="index">
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
                    :rules="textAreaRules"
                    counter
                    rows="2"
                     row-height="50"
                    auto-grow
                    label="Additional information"
                    class="mt-1"
                    v-model="form.additional"
                  ></v-textarea>
                      </v-col>
                  <v-col col="12">
                    <v-divider></v-divider>
                    <v-checkbox v-model="form.accepted_terms">
      <template v-slot:label>
        <div>
          By clicking submit, you certify that all the given information
is true and correct.
        </div>
      </template>
    </v-checkbox>
                  </v-col>
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
           @click="$store.commit('SET_REPORT_DIALOG',false)"
          >
            Cancel
          </v-btn>
          <v-btn
            color="blue darken-1"
            :text="!invalid && form.accepted_terms ? false : true"
           :loading="loading"
            type="submit"
            :disabled="!invalid && form.accepted_terms ? false : true"
            @click.prevent="submit()"
          >
          Submit
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
import { required, email, max } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import { mapGetters,mapMutations } from 'vuex'
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

  extend('email', {
    ...email,
    message: 'Email must be valid',
  })

  export default {
   data:()=>{
     return{
      loading: false,
       form:{
        date: new Date().toISOString().substr(0, 10),
        now:false,
        parish:'',
        city:'',
        street:'',
        type:'',
        details:'',
        accepted_terms:false,
        hasWitness:false,
        witnesses:[],
        additional:''
       },
       temp_name:'',
       temp_phone:'',
       textAreaRules:[v=> v.length <= 1000 || 'Max 250 chracters'],
      	menu: false,

     }
   },
   computed:{
     ...mapGetters(['reportTypes','parishes','reportDialog']),
     computedDateFormatted() {
			return this.formatDate(this.form.date);
    },
   },
   methods:{
     deleteWitness(index){
       this.form.witnesses.splice(index,1);
     },
     addWitness(){
       if(this.temp_name != "" && this.temp_phone !=""){
         this.form.witnesses = [...this.form.witnesses,{
           name:this.temp_name,
           phone:this.temp_phone
         }]
         this.temp_name ="";
         this.temp_phone ="";
       }
     },
     submit(){
      this.loading = true;
       this.$refs.observer.validate();
        Api().post("/create",this.form)
        .then((response) => {
          this.loading = false;
          //close form
          this.$store.commit('SET_REPORT_DIALOG',false)
          this.$refs.form.reset()


          //show success
          this.$store.commit('SET_SUCCESS_DIALOG',{
          content:response.data.reference_number,
          message:'Your report has been submmited for review and approval. Save this reference number to track your report.',
          visible:true,
          title:'Success'
        });
        })
     },
     		formatDate(date) {
			if (!date) return null;

			const [year, month, day] = date.split("-");
			return `${month}/${day}/${year}`;
		},
		parseDate(date) {
			if (!date) return null;

			const [month, day, year] = date.split("/");
			return `${year}-${month.padStart(2, "0")}-${day.padStart(2, "0")}`;
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
