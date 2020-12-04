<template>
  <div>
  <v-row justify="center">
    <v-dialog v-model="missingReportDialog.dialog"
       scrollable max-width="750px"
       style="z-index:9999"
       overlay-color="#8c95a6"
       persistent
       >
      <v-card :loading="loading">
        <v-card-title>
          <span class="text-small text-bolder">{{mode == 'add' ? 'Report Missing Person' : 'Edit Report'}}</span>
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
                <v-card flat style="margin:0 !important">
                  <v-card-subtitle>Basic information & Address</v-card-subtitle>
                  <v-card-title>
                    <v-row>
              <v-col cols="12"
               sm="6"
                md="6">
                      <validation-provider name="fname" rules="required">
											<v-text-field
												label="First name"
												v-model="form.fname"
												hide-details="auto"
												slot-scope="{ errors }"
												:error-messages="errors"
												required
											></v-text-field>
										</validation-provider>
              </v-col>
                       <v-col cols="12"
                           sm="6"
                            md="6"
                            >
                      <validation-provider name="lname" rules="required">
											<v-text-field
												label="Last name"
												v-model="form.lname"
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
                md="6"
              >
               <validation-provider name="gender" rules="required">
											<v-select
												:items="['Male','Female']"
												v-model="form.gender"
												label="Select Gender"
												slot-scope="{ errors }"
												:error-messages="errors"
												required
											></v-select>
										</validation-provider>
              </v-col>

                               <v-col cols="12"
                           sm="6"
                            md="6"
                            >
                      <validation-provider name="age">
											<v-text-field
												label="Age"
												v-model="form.age"
                        type="number"
												hide-details="auto"
											></v-text-field>
										</validation-provider>
              </v-col>

                         <v-col
                cols="12"
                sm="6"
                md="6"
              >
               <validation-provider name="parish" rules="required">
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
												label="Street address"
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
                <v-card flat style="margin:0 !important">
                  <v-card-subtitle>Detail information</v-card-subtitle>
                  <v-card-title>
                    <v-row>
                      <v-col
                       cols="12"
                       sm="6"
                       md="4"
                        >
                      <validation-provider name="height">
											<v-text-field
												label="Height"
												v-model="form.attributes.height"
												hide-details="auto"
											></v-text-field>
										</validation-provider>
                      </v-col>

                       <v-col
                       cols="12"
                       sm="6"
                       md="4"
                        >
                      <validation-provider name="eye_color">
											<v-text-field
												label="Eye color"
												v-model="form.attributes.eye_color"
												hide-details="auto"
											></v-text-field>
										</validation-provider>
                      </v-col>

                           <v-col
                       cols="12"
                       sm="6"
                       md="4"
                        >
                      <validation-provider name="hair_color">
											<v-text-field
												label="Hair color"
												v-model="form.attributes.hair_color"
												hide-details="auto"
											></v-text-field>
										</validation-provider>
                     </v-col>


                           <v-col
                            cols="12"
                            sm="6"
                            md="4"
                             v-if="image">
                             <v-avatar
                             tile
                              class="profile"
                            color="grey"
                            size="164"
                             :loading="!image"
                             >
                             <v-img :src='previewFile()'></v-img>
                             </v-avatar>
                           </v-col>

                             <v-col
                            cols="12"
                            sm="6"
                            md="4" v-if="missingReportDialog.mode == 'edit' && !image">
                            <v-avatar
                             tile
                              class="profile"
                            color="grey"
                            size="164"
                            :loading="!temp_img"
                             >
                               <v-img :src='temp_img'></v-img>
                           </v-avatar>
                           </v-col>



                              <v-col cols="12">
                            	<validation-provider name="image" :rules="missingReportDialog.mode == 'add' ? 'required' : ''">
                                <v-file-input
                                    v-model="image"
                                    color="deep-grey accent-4"
                                    counter
                                    :label="missingReportDialog.mode == 'add' ? 'Upload missing person photo' : 'Edit change photo' "
                                     slot-scope="{ errors }"
                                    :error-messages="errors"
                                    required
                                    placeholder="Upload photo"
                                    accept="image/png, image/jpeg, image/bmp"
                                    append-icon="mdi-camera"
                                    :show-size="1000"
                                >
                                    <template v-slot:selection="{ index, text }">
                                    <v-chip
                                        v-if="index < 2"
                                        color="deep-grey accent-4"
                                        dark
                                        label
                                        small
                                    >
                                        {{ text }}
                                    </v-chip>

                                    <span
                                        v-else-if="index === 2"
                                        class="overline grey--text text--darken-3 mx-2"
                                    >
                                    </span>
                                    </template>
                                </v-file-input>
                            	</validation-provider>
                     </v-col>

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
                    v-model="form.last_seen_details"
                    slot-scope="{ errors }"
										:error-messages="errors"
										required
                  ></v-textarea>
              	</validation-provider>
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
           :loading="loading"
            type="submit"
            :disabled="invalid"
            @click.prevent="submit()"
          >
          Save
          </v-btn>
        </v-card-actions>
            </v-form>
           </validation-observer>
                </v-card-text>
      </v-card>
    </v-dialog>
  </v-row>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex';
import { required, max, image } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import MissingPerson from '../apis/MissingPerson';

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
  data:() => ({
    loading:false,
    mode:'add',
    id:null,
    form:{
      fname:'',
      lname:'',
      gender:'',
      age:null,
      attributes:{
       height:null,
       hair_color:null,
       eye_color:null
      },
      parish:'',
      city:'',
      street:'',
      last_seen_details:'',
    },
    image:null,
    temp_img:''
  }),
   computed:{
     ...mapGetters(['parishes','missingReportDialog']),
     ...mapState(['missingDialog']),
   },

   methods:{
      previewFile(){
      return  URL.createObjectURL(this.image)
     },
     editFormData(){
      this.mode = this.missingReportDialog.mode
      this.id =this.missingReportDialog.report.id
      this.form = {
      fname:this.missingReportDialog.report.fname,
      lname:this.missingReportDialog.report.lname,
      id:this.missingReportDialog.report.id,
      gender:this.missingReportDialog.report.gender,
      age:this.missingReportDialog.report.age,
      attributes:{
       height:JSON.parse(this.missingReportDialog.report.attributes).height,
       hair_color:JSON.parse(this.missingReportDialog.report.attributes).hair_color,
       eye_color:JSON.parse(this.missingReportDialog.report.attributes).eye_color
      },
      parish:this.missingReportDialog.report.address.parish,
      city:this.missingReportDialog.report.address.city,
      street:this.missingReportDialog.report.address.street,
      last_seen_details:this.missingReportDialog.report.last_seen_details,
    }
    this.temp_img = 'http://localhost:8000/storage/'+this.missingReportDialog.report.image.path
     },
     closeDialog(){
       this.loading = false;
      this.$refs.form.reset()
       this.$refs.observer.reset()
       this.$store.commit('SET_MISSING_REPORT_DIALOG',false)
     },
     submit(){
        this.loading = true;
       this.$refs.observer.validate();
             let formData = new FormData();
                 if(this.image){
                  formData.append("image", this.image, this.image.name);
                 }
             //other data
            for(const [key, value] of Object.entries(this.form)){
                  formData.append(key,this.checkKey(key,value));
            }

             MissingPerson.create(formData,this.mode,(this.id ? this.id : ''))
             .then((res)=>{
                this.loading = false;
                //update state
                this.$store.commit('ADD_MISSING_REPORT',{
                  report:res.data,
                  mode:this.mode
                });
                this.closeDialog();

             }).catch((err) =>{
               alert(err);
                this.closeDialog();
             });

     },
            checkKey(key,value){
              return key === "attributes" ?  JSON.stringify(value) : value;
            }

   },

watch:{
missingDialog(newValue, oldValue){
  if(newValue.mode == 'edit'){
    this.editFormData();
  }
}
},


   components:{
     ValidationObserver,
     ValidationProvider
   }
}
</script>

<style>

</style>
