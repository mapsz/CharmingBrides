<template>
  <div class="container-fluid category-services-wrapper py-5">    
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
            id:this.pData,
            services:[],
            name:'',
          }
        },
        async mounted() {   
          this.getData();

        },
        methods: {
          async getData(){

            let l = this.loading('.category-services-wrapper');
            let r = await this.ax('get', '/service/get/category/'+this.id);            

            if(!r){
              this.hideLoading(l);
              return false;
            } 

            this.services = r['services'];
            this.name = r['name'];
            this.hideLoading(l);           

          } 
        }               
    }
</script>
