<template>
<div>
  <navigation></navigation>
    <div class="container my-16 justify-content-center">
      <v-row>
        <v-col cols="12" md="10">
                    <v-card outlined>
            <v-card-title>Profile</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
              <v-row >
                <v-col cols="12" md="6">
                 <v-text-field
                    rounded
                    filled
                    dense
                    label="Full name"
                 >

                 </v-text-field>
                </v-col>
                       <v-col cols="12" md="6">
                 <v-text-field
                    rounded
                    filled
                    dense
                    label="Email"
                    readonly
                 >

                 </v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </div>
</div>
</template>

<script>
import Navigation from '../../components/Navigation.vue'
import User from '../../apis/User'
import { required, email, max } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })
  setInteractionMode('eager')

export default {
  components: { Navigation, ValidationObserver, ValidationProvider },

}
</script>

<style>

</style>
