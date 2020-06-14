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
          <!-- Price -->
          <center>
            <span class="price">{{service.price}}</span> 
          </center>
        </div> 

        <!-- Text -->
        <div style="font-size: 16pt;">
          <div v-if="buyError" class="text-danger">
            {{buyError}}
          </div>
          <div v-if="buySuccess" class="text-success">
            {{buySuccess}}
          </div>
        </div>
        <!-- Comment -->
        <div v-if="!buy" class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon3">Commentary</span>
          </div>
          <input v-model="comment" type="text" class="form-control" id="basic-url">
        </div>        
        <!-- Buy -->
        <div v-if="!buy"  class="d-flex justify-content-around">        
          <a :href="'/order?cat=service&id='+service._id">
            <button class="btn btn-primary">Buy via PayPal</button>
          </a> 
          <button @click="buyService()" class="btn btn-primary">Buy via Site Balance</button>
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
            comment:"",
            lo:false,
            buy:false,
            buyError:false,
            buySuccess:false,
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

          },
          async buyService(){
            this.buy = true;
            let r = await this.ax('put', '/service/buy/',{id:this.id,comment:this.comment});
            
            if(r == 2){
              this.buy = false;
              this.buyError = 'Insufficient funds!'
            }             
            if(r == 1){
              this.buySuccess = 'Done!'
            } 
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