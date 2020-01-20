<template>
  <div>
    <center>
      <h4 class="mt-0"><strong>{{pService.name}}</strong></h4>     
      <img 
        v-if="photo"
        class="service-logo align-self-center mr-3" 
        :src="'/'+photo" 
        alt="Generic placeholder image"
      >      <br>  
      <span class="price">{{pService.price}}</span>             
      <a :href="(pCategory ? '/service/category/' : '/service/') + pService.id">
        <button class="btn btn-primary">More</button>
      </a>
    </center> 
  </div>
</template>

<script>
    export default {        
      props:['p-service','p-category'],
      computed:{
        photo:function(){
          if(this.pService.image == undefined)
            return false;

          if(this.pService.image[0] == undefined)
            return false;

          return this.pService.image[0];
        },
      },
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