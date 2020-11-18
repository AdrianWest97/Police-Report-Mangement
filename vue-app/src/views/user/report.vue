<template>
<v-container>
<div class="d-flex flex-column my-16">
<v-breadcrumbs :items="items">
    <template v-slot:item="{ item }">
      <router-link
        :to="item.to"
      >
        {{item.text.charAt(0).toUpperCase() + item.text.slice(1)}}
      </router-link>
    </template>
  </v-breadcrumbs>
  <div class="mx-6"><h4>{{$route.name.charAt(0).toUpperCase() + $route.name.slice(1)}}</h4></div>
</div>

        <v-row v-if="$route.name === 'report'">
          <v-col>
            <v-sheet
              min-height="70vh"
              rounded="lg"
              class="bg-transparent"
            >
    <v-row>
    <v-col  cols="12" md="12" lg="4" sm="12"  id="report-card">
		<a href="#" @click.prevent="$store.commit('SET_REPORT_DIALOG',true)" class="card2">
       <div class="d-flex flex-column justify-content-center">
         <div class="card-icon p-3">
           <img src="../../assets/svg/report.svg" alt="track"/>
         </div>
      <h3>Make a Report</h3>
       <p class="small">Make a report of a crime or suspicious activities</p>
      </div>
    </a>

      </v-col>
    <v-col  cols="12" md="12" lg="4" sm="12"  id="report-card">
		<router-link to="/dashboard/report" class="card2">
       <div class="d-flex flex-column justify-content-center">
         <div class="card-icon p-3">
           <img src="../../assets/svg/delivery.svg" alt="track"/>
         </div>
      <h3>Track your Reports</h3>
       <p class="small">Track your report progress to see when it has been approved.</p>
      </div>
    </router-link>
</v-col>
    <v-col  cols="12" md="12" lg="4" sm="12"  id="report-card">
		<router-link to="/report/my-reports" class="card2">
       <div class="d-flex flex-column justify-content-center">
         <div class="card-icon p-3">
           <img src="../../assets/svg/delivery.svg" alt="track"/>
         </div>
      <h3>My Reports</h3>
       <p class="small">Track your report progress to see when it has been approved.</p>
      </div>
    </router-link>
</v-col>
    </v-row>
            </v-sheet>
          </v-col>
        </v-row>
   <new-report></new-report>
               <router-view></router-view>

</v-container>
</template>

<script>
import NewReport from "../../components/NewReport.vue"
import Navigation from "../../components/Navigation.vue"

  export default {
    data: () => ({

    }),
    components:{
      NewReport,
      Navigation
    },
    computed:{
      items:function(){
      let pathArray = this.$route.path.split("/")
      pathArray.shift()
      let breadcrumbs = pathArray.reduce((breadcrumbArray, path, idx) => {
        breadcrumbArray.push({
          path: path,
          to: breadcrumbArray[idx - 1]
            ? "/" + breadcrumbArray[idx - 1].path + "/" + path
            : "/" + path,
          text: this.$route.matched[idx].meta.breadCrumb || path,
        });
        return breadcrumbArray;
      }, [])
      return breadcrumbs;
    }
    },
  }
</script>

<style lang="scss" scoped>

</style>
