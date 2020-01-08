<template>
    <div class="container">
      <h1>Services</h1>

      <div class="service-wrapper py-5">
        <div class="media">
          <img 
            v-if="photo"
            class="service-logo align-self-center mr-3" 
            :src="'/'+photo" 
            alt="Generic placeholder image"
          >
          <div class="media-body">
            <h4 class="mt-0">{{service.name}}</h4>
            <p>{{service.description}}</p>
            

          </div>
        </div>  
        <div class="service-footer">
          <center>
            <span class="price">{{service.price}}</span> 
            <span class="order-button"><button class="btn btn-primary">Order Now!</button></span>
          </center>
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
            service:{
              _id:0,
              name:"",
              description:"",
              price:"",
            },
            lo:false
          }
        },           
        computed:{
          photo:function(){
            if(this.service.image == undefined)
              return false;

            if(this.service.image[0] == undefined)
              return false;

            return this.service.image[0];
          },
        },
        async mounted() {
          this.lo = this.showLoading('.service-wrapper');     
          this.getService();

        },
        methods: {
          async getService(){

            let r = await this.ax('get', '/service/get/'+this.id);
            

            if(!r) return false;

            this.service = r;



            this.hideLoading(this.lo);
            return r;

          }
        }
    }
</script>

<style scooped>
  
  .service-logo{
    max-width: 200px;
  }

  .price{
    font-size: 18pt;
    padding-right: 30px;
  }

  .price::after{
    content: "€";
  }


</style>