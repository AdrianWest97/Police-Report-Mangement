<template>
  <nav>
    <v-toolbar flat>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="text-uppercase grey--text">
        <span class="font-weight-light">Report Management</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn @click="logout()" text color="grey">
        <span>Sign Out</span>
        <v-icon right>mdi-logout</v-icon>
      </v-btn>
    </v-toolbar>
    <v-navigation-drawer
     v-model="drawer"
      app
      class="">

        <v-list>
        <v-list-item
          v-for="item in items"
          :key="item.title"
          :to="item.route"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>



    </v-navigation-drawer>
     <div class="container-fluid grey lighten-4 h-screen">

     <v-row  v-if="$route.path === '/dashboard'">
     <card v-for="card in cards" :key="card.text" :text="card.text" :icon="card.icon" :value="card.value">
     </card>
          <v-col cols="12" sm="4">
              <v-sheet
              rounded="lg"
              min-height="50"
              class="p-5"
            >
           <chart-by-type></chart-by-type>
            </v-sheet>
          </v-col>
                <v-col cols="12" sm="8">
              <v-sheet
              rounded="lg"
              min-height="50"
               class="p-5"
            >
               <chart-by-parish></chart-by-parish>
            </v-sheet>
          </v-col>

          <v-col cols="12">
              <v-sheet
              rounded="lg"
              min-height="50"
               class="p-5"
            >
               <country-map></country-map>
          </v-sheet>
          </v-col>




        </v-row>
             <router-view>
     </router-view>
     </div>
  </nav>
</template>

<script>
import Card from '../../components/admin/Card.vue'
import ChartByType from '../../components/charts/ChartByType.vue';
import ChartByParish from '../../components/charts/ChartByParish.vue';
import Report from '../../apis/Report';
import User from '../../apis/User.js';
import CountryMap from '../../components/CountryMap.vue';
export default {
  components:{
    Card,
    ChartByType,
    ChartByParish,
    CountryMap,
  },
  data() {
    return {
      drawer: true,
       items: [
          { title: 'Dashboard', icon: 'mdi-view-dashboard',route:'/dashboard' },
          { title: 'Reports', icon: 'mdi-clock-time-eight-outline ',route:'/dashboard/pending-reports' },
          { title: 'Missing persons', icon: 'mdi-account-box',route:'/dashboard/missing-person' },
          { title: 'Active Users', icon: 'mdi-account-group', route:'/dashboard/active-users' },
          { title: 'Logout', icon: 'mdi-logout', route:'/' },
        ],
        cardData:null,
        cards:[],
         showInfo: false,
      infoWIndowContext: {
        position: {
          lat: 44.2899,
          lng: 11.8774
        }
      },
    }
  },

  methods:{
      toggleInfoWindow (context) {
      this.infoWIndowContext = context
      this.showInfo = true
    },
    infoClicked(context) {
    },
    getCardData(){
      Report.getCardData()
      .then((res) => {
        this.cardData = res.data
        this.cards = [...this.cards,
         {
         text:'Active Users',
          icon:require('../../assets/svg/group.svg'),
          value:this.cardData.users
          },
          {
         text:'Total Visitors',
          icon:require('../../assets/svg/website.svg'),
          value:1
          },
          {
         text:'Pending Reports',
          icon:require('../../assets/svg/pending.svg'),
          value:this.cardData.pending
          },
          {
         text:'Total Reports',
          icon:require('../../assets/svg/report.svg'),
          value:this.cardData.total_reports
          }
     ]
        });
    },

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

  created(){
    this.getCardData();
  }
}
</script>


<style>
.h-screen {
  height: 100vh;
}
</style>
