<template>
    <v-container fluid fill-height>
      <v-row align="center" justify="center">
        <v-col cols="12" md="6" lg="4" sm="12">
          <v-card  outlined  :loading="logginIn">
            <v-card-title class="headline font-weight-bolder">Login</v-card-title>
            <v-spacer></v-spacer>
            <v-card-text>
          <validation-observer
            ref="observer"
            v-slot="{ invalid }"
          >
    <form @submit.prevent="submit">
      <validation-provider
        v-slot="{ errors }"
        name="Email "
        rules="required|email"
      >
         <v-text-field
          v-model="form.email"
         filled
          :error-messages="errors"
          label="Enter your email"
          required
          rounded
           dense
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
           filled
            rounded
            dense
            label="Enter your password"
             @click:append="show = !show"
          ></v-text-field>
      </validation-provider>

        <div class="form-group">
         <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
        </div>


    <div class="d-flex justify-content-between">
             <v-btn
        class="mr-4"
        type="submit"
        :disabled="invalid"
        color="primary"
        rounded
      >
        Login
      </v-btn>

      <router-link to="/register" class="font-weight-bold">Create Account</router-link>
    </div>
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
      logginIn:false,
      form: {
        email: '',
        password: ''
      },
      errors: [],
      show:false
    }
  },

  methods: {
    submit(){
      this.$refs.observer.validate();
      this.login();
    },
    login () {
      this.logginIn = true;
      User.login(this.form)
        .then(response => {
          this.$store.commit('LOGIN', true)
          localStorage.setItem('token', response.data)
          this.checkAdminRights();
          this.logginIn = false;
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors
            this.logginIn = false;
          }
        })

    },
    checkAdminRights(to, from, next) {
    // check if the user is admin
  User.auth()
    .then((res) => {
      if (res.data.is_admin) {
    this.$router.push({ name: 'Dashboard' })
      } else {
    this.$router.push({ name: 'Home' })
      }
    });
}
  },
  components:{
     ValidationProvider,
      ValidationObserver,
  }
}
</script>
