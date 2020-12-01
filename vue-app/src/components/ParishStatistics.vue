<template>
    <v-select
     label="Select parish"
      :items="$store.getters.parishes"
      rounded filled
      v-model="parish"
      @change="fetchStatistics()"
      ></v-select>
</template>

<script>
import Report from '../apis/Report'
export default {
data:() =>({
  parish: ''
}),
methods:{
  fetchStatistics(){
    Report.getParishStatistic(this.parish)
    .then((res)=>{
      console.log(res.data)
    })
  }
}
}
</script>

<style>

</style>
