<template>
  <div class="container-fluid all-services-wrapper py-5">

    <h1 class="text-center">Services</h1>

    <div 
      v-if="services.categories != undefined"  
      class="service-categories"
    >
      <h2>Categories</h2>
      <div class="row">
        <div v-for="s in services.categories.data" class="col">
          <service-cart :p-service="s" :p-category="true"></service-cart>
        </div>
      </div>      
    </div>

    <div 
      v-if="services.services != undefined"  
      class="services"
    >
      <h2>Services</h2>
      <div class="row">
        <div v-for="s in services.services.data" class="col">
          <service-cart :p-service="s" ></service-cart>
        </div>
      </div>      
    </div>

  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row','p-attr'],
        data(){
          return {
            services:[],
          }
        },          
        mounted() {
          this.getServices();
        },
        methods: {
          async getServices(){

            let l = this.loading('.all-services-wrapper');
            let r = await this.ax('get', '/service/get/all');            

            if(!r){
              this.hideLoading(l);
              return false;
            } 

            this.services = r;

            this.hideLoading(l);

          }
        }
    }
</script>
