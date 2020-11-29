<template>

  <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top" style="z-index:1">
  <div class="container">
    <a class="navbar-brand" href="#">REPORT <span class="text-danger"> CRIME</span> ONLINE</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <router-link to="/" class="nav-link" href="#">
                <span class="sr-only">(current)</span>
              </router-link>
        </li>
        <li class="nav-item">
          <router-link v-if="!isLoggedIn" to="/login" class="nav-link" href="#">Login</router-link>
        </li>
          <li class="nav-item">
          <router-link v-if="!isLoggedIn" to="/register"  class="nav-link" href="#">Register</router-link>
        </li>
         <li class="nav-item">
          <a v-if="isLoggedIn" href="#"  class="nav-link" @click.prevent="logout()">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

</template>
<script>
import User from '../apis/User'
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters(['isLoggedIn'])
  },

  methods: {
    logout () {
      User.logout().then(() => {
        localStorage.removeItem('token')
        this.$store.commit('LOGIN', false)
        if(this.$route.path != "/"){
        this.$router.push({ path: '/' })
        }
      }).catch((err)=>{
      if(err.response.status == 401){
        localStorage.removeItem('token')
         this.$store.commit('LOGIN', false)
        if(this.$route.path != "/"){
        this.$router.push({ path: '/' })
      }
    }
      });
  }
  },

  mounted(){
  }
}
</script>

<style scoped>
.router-link-exact-active {
  transition: all 0.25s;
}
</style>
