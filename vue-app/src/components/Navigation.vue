<template>

  <!-- Navigation -->
<nav class="navbar navbar-expand-lg p-0 navbar-light bg-light shadow-sm fixed-top"  style="z-index:1">
  <div class="container">
    <router-link to="/" class="font-weight-bold text--darken-1">REPORT <span class="text-danger"> CRIME</span> ONLINE</router-link>
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


  <li v-if="isLoggedIn">
  <div>
    <v-menu >
       <template v-slot:activator="{ on: menu, attrs }">
            <v-btn
              icon
              v-bind="attrs"
              v-on="{...menu }"
            >
            <v-icon color="black">mdi-account-circle-outline</v-icon>
            </v-btn>
          </template>

      <v-list dense>
        <v-list-item
          v-for="(item, index) in items"
          :key="index"
          @click.prevent="menuActionClick(item.action)"
        >

           <v-list-item-content>
          <v-list-item-title>
            <a class="dropdown-item p-0" href="#">
              <v-icon
               medium
               color="black">
               {{item.icon}}</v-icon>&nbsp;&nbsp;&nbsp;{{ item.title }}</a>
            </v-list-item-title>
            </v-list-item-content>

        </v-list-item>
      </v-list>
    </v-menu>
  </div>
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

     data: () => ({
      items: [
        { title: 'Reports',icon:'mdi-clipboard-edit-outline',action:'reports' },
        { title: 'Settings',icon:'mdi-account-cog-outline',action:'profile' },
        { title: 'Logout', icon:'mdi-power',action:'logout' },

      ],
    }),

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
  },
    menuActionClick(action) {
      if (action === "logout") {
        this.logout();
      } else if (action === "profile") {
        this.$router.push({path: '/profile'})
      }else if (action === "reports") {
        this.$router.push({path: '/report'})
      }
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

a {
    color: grey !important;
    text-decoration: none;
}


</style>
