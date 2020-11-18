<template>
    <v-container fluid fill-height>
      <v-row align="center" justify="center">
        <v-col cols="12" md="6" lg="4" sm="12">
          <v-card elevation="2">
            <v-card-title class="headline font-weight-bolder">Register</v-card-title>
            <v-spacer></v-spacer>
            <v-card-text>
          <validation-observer
            ref="observer"
            v-slot="{ invalid }"
          >
    <form @submit.prevent="submit">
        <validation-provider
        v-slot="{ errors }"
        name="Full name"
        rules="require"
      >
         <v-text-field
          v-model="form.name"
          outlined
          rounded
          :error-messages="errors"
          label="Enter your Full name"
          required
        ></v-text-field>
      </validation-provider>
      <validation-provider
        v-slot="{ errors }"
        name="Email "
        rules="required|email"
      >
         <v-text-field
          v-model="form.email"
          outlined
          rounded
          :error-messages="errors"
          label="Enter your email"
          required
        ></v-text-field>
      </validation-provider>
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
            outlined
            rounded
            label="Enter your Password"
             @click:append="show = !show"
          ></v-text-field>
      </validation-provider>

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
            outlined
            rounded
            label="Confirm Password"
          ></v-text-field>
      </validation-provider>

        <div class="form-group">
         <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
        </div>
        <v-btn
        class="mr-4"
        type="submit"
        :disabled="invalid"
        color="primary"
        rounded
      >
        Create account
      </v-btn>
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
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errors: []
    }
  },

  methods: {
     submit(){
      this.$refs.observer.validate();
      this.register();
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
