<template>
<div>


  <v-row justify="center">
    <v-dialog v-model="getEditReport.visible"
       scrollable max-width="800px"
       style="z-index:9999"
       overlay-color="#8c95a6"
       persistent
       >
       <v-card  class="text-center p-5" v-if="getEditReport.report == null">
           <v-card-text>
              Please wait...
             <v-progress-circular indeterminate></v-progress-circular>
           </v-card-text>
          </v-card>
      <v-card v-else :loading="loading">
        <v-card-title>
          <span class="text-small text-bolder">Edit Report</span>
        </v-card-title>
        <v-divider></v-divider>
                <v-card-text>
          <validation-observer
            ref="observer"
            v-slot="{ invalid }"
          >
          <form @submit.prevent="submit" ref="form">
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
												v-model="getEditReport.report.type.type"
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
												v-model="getEditReport.report.date"
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
												v-model="getEditReport.report.address.parish"
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
												v-model="getEditReport.report.address.city"

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
												v-model="getEditReport.report.address.street"
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
                    counter
                    rows="2"
                     row-height="50"
                    auto-grow
                    label="Details"
                    class="mt-1"
                    v-model="getEditReport.report.details"
                    	slot-scope="{ errors }"
												:error-messages="errors"
												required
                  ></v-textarea>
                      	</validation-provider>
                      </v-col>
                      <v-col cols="12">
                   <v-checkbox
                  v-model="getEditReport.report.hasWitness"
                  :label="'Where there any witneeses to the inccident? '"
                  class="m-0 p-0"
                ></v-checkbox>

                      <ul v-show="getEditReport.report.witnesses.length > 0" class="list-group m-0 mb-2 p-0" v-for="(witness,index) in getEditReport.report.witnesses" :key="index">
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
                <div v-if="getEditReport.report.hasWitness"  class="d-flex flex-row w-100">
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
                    label="Additional information"
                    class="mt-1"
                    v-model="getEditReport.report.additional"
                  ></v-textarea>
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
           @click="closeDialog()"
          >
            Cancel
          </v-btn>
          <v-btn
            color="success"
            :text="false"
           :loading="loading"
            type="submit"
            :disabled="invalid"
            @click.prevent="submit()"
          >
          Save
          </v-btn>
        </v-card-actions>
        </form>
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
import { required, email, max } from 'vee-validate/dist/rules'
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

  extend('email', {
    ...email,
    message: 'Email must be valid',
  })

  export default {
   data:()=>{
     return{
      loading: false,
      date: new Date().toISOString().substr(0, 10),
       temp_name:'',
       temp_phone:'',
      //  textAreaRules:[v=> v.length <= 1000 || 'Max 250 chracters'],
      	menu: false,

     }
   },
   computed:{
     ...mapGetters(['reportTypes','parishes','getEditReport']),
     computedDateFormatted() {
			return this.formatDate(this.getEditReport.report.date);
    },
   },
   methods:{
     deleteWitness(index){

       Report.deleteWitness( this.getEditReport.report.witnesses[index].id)
       .then((res) => this.getEditReport.report.witnesses.splice(index,1))
     },
     addWitness(){
       if(this.temp_name != "" && this.temp_phone !=""){
         this.getEditReport.report.witnesses = [...this.getEditReport.report.witnesses,{
           name:this.temp_name,
           phone:this.temp_phone
         }]
         this.temp_name ="";
         this.temp_phone ="";
       }
     },
     submit(){
       this.loading = true;
     var form = {
       type:this.getEditReport.report.type.type,
       parish:this.getEditReport.report.address.parish,
       city:this.getEditReport.report.address.city,
       street:this.getEditReport.report.address.street,
       details:this.getEditReport.report.details,
       additional:this.getEditReport.report.additional,
       hasWitness:this.getEditReport.report.hasWitness,
       witnesses:this.getEditReport.report.witnesses,
       date:this.getEditReport.report.date
     }
      Report.update(form,this.getEditReport.report.id)
      .then((res) => {
               this.loading = false;
        this.closeDialog();
            this.$store.commit('SET_SNACK_BAR',{
            visible:true,
            content:"Update success"
             })
      }).catch((err)=>        this.loading = true)
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

      closeDialog(){
       this.loading = false;
      this.$refs.form.reset()
       this.$refs.observer.reset()
          this.$store.commit('SET_EDIT_REPORT_DIALOG',{
          report:null,
          visible:false
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
