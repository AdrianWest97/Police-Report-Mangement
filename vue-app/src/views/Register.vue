<template>
    <v-container fluid fill-height>
      <v-row align="center" justify="center">
        <v-col cols="12" md="6" lg="7" sm="12">
          <v-card elevation="2">
            <v-card-title class="headline font-weight-bolder">Create Account</v-card-title>
            <v-spacer></v-spacer>
            <v-card-text>
          <validation-observer
            ref="observer"
            v-slot="{ invalid }"
          >
    <form @submit.prevent="submit">
      <v-row>
        <v-col cols="12" sm="6">
      <validation-provider
        v-slot="{ errors }"
        name="Full name"
        rules="required"
      >
         <v-text-field
          v-model="form.name"
         filled
          rounded
          :error-messages="errors"
          label="Enter your Full name"
          required
           dense
        ></v-text-field>
      </validation-provider>
        </v-col>

              <v-col cols="12" sm="6">
      <validation-provider
        v-slot="{ errors }"
        name="TRN"
        rules="required"
      >
         <v-text-field
          v-model="form.trn"
          filled
          :maxlength="9"
          rounded
          :error-messages="errors"
          label="Enter your TRN"
          required
          type="number"
           dense
        ></v-text-field>
      </validation-provider>
        </v-col>

        <v-col cols="12" sm="6">
      <validation-provider
        v-slot="{ errors }"
        name="Email "
        rules="required|email"
      >
         <v-text-field
          v-model="form.email"
         filled
          rounded
          :error-messages="errors"
          label="Enter your email"
          required
           dense
        ></v-text-field>
      </validation-provider>
        </v-col>
            <v-col cols="12" sm="6">
      <validation-provider
        name="Phone "
        rules="required"
      >
     <v-text-field
          v-model="form.phone"
         filled
          rounded
          :error-messages="errors"
          label="Enter your phone #"
          required
          dense
        ></v-text-field>
      </validation-provider>
        </v-col>

              <v-col cols="12" sm="6">
      <validation-provider
       v-slot="{ errors }"
        name="Password "
        rules="required"
      >
         <v-text-field
            v-model="form.password"
            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show ? 'text' : 'password'"
            :error-messages="errors"
            name="password"
           filled
            rounded
             dense
            label="Enter your Password"
             @click:append="show = !show"
          ></v-text-field>
      </validation-provider>
      </v-col>


      <v-col cols="12" sm="6">
         <validation-provider
       v-slot="{ errors }"
        name="Password "
        rules="required"
      >
         <v-text-field
            v-model="form.password_confirmation"
            :type="'password'"
            :error-messages="errors"
            name="password"
           filled
            rounded
             dense
            label="Confirm Password"
          ></v-text-field>
      </validation-provider>
      </v-col>

       <v-col
         cols="12"
          sm="6"
        >
        <validation-provider name="type" rules="required">
				<v-select
				:items="$store.state.parishes"
					v-model="form.parish"
					label="Select Parish"
					slot-scope="{ errors }"
				   :error-messages="errors"
					required
          filled
          rounded
           dense
				></v-select>
			</validation-provider>
   </v-col>
            <v-col cols="12" sm="6">
      <validation-provider
        v-slot="{ errors }"
        name="City"
        rules="required"
      >
         <v-text-field
          v-model="form.city"
         filled
          rounded
          :error-messages="errors"
          label="City"
          required
           dense
        ></v-text-field>
      </validation-provider>
        </v-col>


            <v-col cols="12">
      <validation-provider
        v-slot="{ errors }"
        name="street"
        rules="required"
      >
         <v-text-field
          v-model="form.street"
         filled
          rounded
          :error-messages="errors"
          label="Street Address"
          required
          dense
        ></v-text-field>
      </validation-provider>
        </v-col>

        <div class="form-group">
         <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
        </div>
      <v-col cols="12" class="d-flex justify-content-end">
       <v-btn
        class="mr-4"
        type="submit"
        color="primary"
        rounded
        large
      >
        Create account
      </v-btn>
      </v-col>
      </v-row>

    </form>
          </validation-observer>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
  </v-container>
</template>

<script>
import User from '../apis/User'
import { required, email, max } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'

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
  data () {
    return {
      show:false,
      form: {
        name: '',
        email: '',
        phone: '',
        TRN: '',
        city:'',
        parish:'',
        password: '',
        password_confirmation: ''
      },
      errors: []
    }
  },

  methods: {
     submit(){
      this.$refs.observer.validate();
    },
    register () {
      User.register(this.form)
        .then(() => {
          this.$router.push({ name: 'Login' })
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors
          }
        })
    }
  },
    components:{
     ValidationProvider,
      ValidationObserver,
  }
}
</script>
