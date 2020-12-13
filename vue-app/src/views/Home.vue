<template>
<div>
  <navigation></navigation>
<header class="masthead bg-dark">
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12 text-center">
        <h1 class="text-bold font-weight-bolder text-white" >Jamaica Crime Reporting <span class="text-danger">Online</span></h1>
        <p class="lead text-white">Creating a safer Jamaica for all</p>
        <v-btn @click="$vuetify.goTo('#start')" rounded outlined color="white" x-large>Get Started</v-btn>
      </div>
    </div>
  </div>
</header>

 <section class="py-5" id="start" style="background-color:#F3F3F3">
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-6">
         <h4 class="font-weight-bolder">What's happening in your area?</h4>
       <p>Find out local information about where you live including
           crime statistics and where your closest station is.</p>
      </div>
     <div class="col">
         <h4 class="font-weight-bolder">Find your area</h4>
       <p>Find out local information about where you live including
 crime statistics and where your closest station is.</p>
   <parish-statistics></parish-statistics>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg" >
  <v-container>
    <h4 class="font-weight-bolder">What else we can help with?</h4>
    <v-row dense>
    <v-col  cols="12" md="6" lg="4" sm="12" >
	<a href="#" @click.prevent="$store.commit('SET_REPORT_DIALOG',true)" class="card2">
       <div class="d-flex flex-column justify-content-center">
         <div class="card-icon p-3">
           <img src="../assets/svg/clock.svg" alt="track"/>
         </div>
      <h3>Quick Report</h3>
       <p class="small">Make a report anonymously or create an account.</p>
      </div>
	</a>
</v-col>
    <v-col  cols="12" md="6" lg="4" sm="12">
		<a href="#" @click="$store.dispatch('showTrackReportDialog')" class="card2">
       <div class="d-flex flex-column justify-content-center">
         <div class="card-icon p-3">
           <img src="../assets/svg/delivery.svg" alt="track"/>
         </div>
      <h3>Track your Report</h3>
       <p class="small">Track your report to see when it has been approved.</p>
      </div>
		</a>
</v-col>

    <v-col  cols="12" md="6" lg="4" sm="12">
		<router-link to="/missing" class="card2">
       <div class="d-flex flex-column justify-content-center">
         <div class="card-icon p-3">
                      <img src="../assets/svg/search.svg" alt="track"/>
         </div>
      <h3>Find missing persons</h3>
       <p class="small">See a list of missing people or report someone missing.</p>
      </div>
    </router-link>
</v-col>
    <v-col  cols="12" md="6" lg="4" sm="12">
		<router-link to="/dashboard/report" class="card2">
       <div class="d-flex flex-column justify-content-center">
         <div class="card-icon p-3">
           <img src="../assets/svg/location.svg" alt="track"/>
         </div>
      <h3>Your area</h3>
       <p class="small">See all criminal and minor activities happening in your area</p>
      </div>
    </router-link>
</v-col>
   </v-row>
  </v-container>
</section>
  <new-report></new-report>
<page-footer></page-footer>
</div>
</template>

<script>
import Navigation from '../components/Navigation'
import PageFooter from '../components/PageFooter.vue'
import ParishStatistics from '../components/ParishStatistics.vue'
import NewReport from "../components/NewReport.vue"
import User from '../apis/User'
import Api from '../apis/Api'


export default {
  name: 'Home',
  components: {
    Navigation,
    ParishStatistics,
    PageFooter,
    NewReport
  },

}
</script>

<style lang="scss" scoped>
.masthead {
  height: 100vh;
  min-height: 500px;
  //  background-image: url('../assets/img/bg.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
   background:
    linear-gradient(
     rgba(0,0,0,0.6),
     rgba(0,0,0,0.6),
    ),
    url('../assets/img/bg.jpg');
  background-position: center center;
  background-size: cover;
}

</style>
