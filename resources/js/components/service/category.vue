<template>
  <div class="container-fluid all-services-wrapper py-5">    
    <h2>{{name}}</h2>
    <div class="row">
      <div v-for="s in services" class="col">
        <service-cart :p-service="s"></service-cart>
      </div>
    </div>       
  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-data'],
        data(){
          return {
            services:$.parseJSON(this.pData)['services'],
            name:$.parseJSON(this.pData)['name'],
          }
        }, 
    }
</script>
