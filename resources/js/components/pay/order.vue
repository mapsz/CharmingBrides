<template>
    <div class="container my-4">
      <h1>Order</h1>
      <div class="order-item row p-4 my-4 ">
        <div class="col">{{pOrder.name}}</div>
        <div class="col">
          <span class="float-right">&euro;{{pOrder.price}}</span>
        </div>
      </div>

      <div v-if="!pay" class="pay-method">
        <h2>Pay Method</h2>
        <div class="row">
          <div class="col">
            <juge-paypal 
              :p-cat="pCat" 
              :p-product-id="pOrder.id" 
              :p-user-id="pUserId" 
              @success-pay="pay = true"
            />
          </div>
        </div>
      </div>
      <div v-if="pay" class="order-confirmed text-center">
        <p>Thank you!</p>
        <p style="font-size: 18pt;">Order confirmed</p>
      </div>
    </div>
</template>

<script>
  export default {        
    mixins: [ ],
    props:['p-cat','p-order','p-user-id'],
    data: function() {
      return {
        pay:false,
      };
    },        
  }
</script>


<style scooped>  
  .order-item{
    background-color:#bcbcbc; 
    border: 1px #8b8b8b solid;
    font-size: 24pt;
    color: #960046;    
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
  }

  .order-confirmed {
      font-size: 24pt;
      color: #bf005a;
  }

</style>