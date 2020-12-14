<template>
          <v-col
            cols="12"
            sm="3"
          >
            <v-sheet
              rounded="lg"
              min-height="50"
            >
             <div class="d-flex flex-column  flex-lg-row justify-content-between p-3">
               <div>
                 <img :src="icon"  width="50px" alt="active users"/>
               </div>
               <div class="d-flex flex-column">
                 <p>{{text}}</p>
                 <h2>{{value}}</h2>
               </div>
             </div>
            </v-sheet>
          </v-col>
</template>

<script>
export default {
  data(){
    return{
      src:""
    }
  },
props:['text','icon','value']
}
</script>

<style>

</style>
