<template>
  <div class="container">
    <h1 class="my-4">Memberships</h1>
    <div class="row membership-list">
      <div 
        v-for="membership in memberships" 
        class="col col-md-6 col-lg-4 membership"
      >
        <div class="media border rounded m-2 p-2 bg-white">
          <img 
            v-if="membership.image[0]"
            :src="membership.image[0]" 
            class="align-self-center mr-3" 
            :alt="membership.name"
          >
          <div class="media-body text-center">   
            <h4 class="card-title text-uppercase">{{membership.name}}</h4>
            <p class="card-text">
              Price for Lady's reply:<br>
              <b>{{membership.long_letter_price}}&#8364</b><br>
              1 minute chat:<br>
              <b>{{membership.chat_price}}&#8364</b><br>
            </p>
            <p class="card-text">
              {{membership.price}}&#8364<br>
              <a :href="'/order?cat=membership&id='+membership.id">
                <button class="btn btn-success">ORDER NOW!</button>
              </a>
            </p>        
          </div>          
        </div>
      </div>
    </div>
    <div class="mt-3">

      <h3>How it works</h3>
      <p>
        <b>STEP 1</b> Choose your membership or try a Start Set<br>
        <b>STEP 2</b> Make a deposit and become a member<br>
        <b>STEP 3</b> Contact ladies and get replies soon!
      </p>
      <p>
        You choose a type of membership you'd like, make a payment and the amount of deposit is added to your Charming Brides account. This deposit later may be used for any of the services at our website, every time you make an order the price for the service will be deducted from your deposit. If your deposit is not enough for ordering the service, the system will inform you about it and suggest making another deposit. The remaining deposit is added to your account along with new deposit you make. You have constant access to the state of your Charming Brides account and you always decide yourself what service to order next.
      </p>
      <p>
        You can try with a START SET and later continue with any other deposit
      </p>

    </div>
  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            memberships:[],
          }
        },               
        mounted() {
          this.getMemberships();
        },
        methods: {
          async getMemberships(){
            let l = this.showLoading('.membership-list');
            let memberships = await this.ax('get','/memberships/get/all');

            if(!memberships){
              this.hideLoading(l); return false;
            }  


            console.log(memberships.data);

            this.memberships = memberships.data;

            this.hideLoading(l);
          }
        }
    }
</script>

<style scooped>
  
  @media (max-width: 576px){
    .membership img{
      width:100px;
    }   
  }
  @media (min-width: 577px) and (max-width: 767px) {
    .membership img{
      width:1287px;
    }       
  }
  @media (min-width: 768px) and (max-width: 991px) {
    .membership img{
      width:128px;
    }       
  }
  @media (min-width: 992px) and (max-width: 1200px) {
    .membership img{
      width:100px;
    }  
  }
  @media (min-width: 1201px){
    .membership img{
      width:128px;
    }        
  }


</style>