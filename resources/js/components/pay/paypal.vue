<template>
  <div>
    <div id="paypal-button"></div>
  </div>
</template>

<script>
export default {
  props:['p-cat','p-product-id','p-user-id'],
  data: function() {
    return {
      //
    };
  },
  mounted() {
    //Load script
    const script = document.createElement("script");
    script.src = "https://www.paypalobjects.com/api/checkout.js";
    script.addEventListener("load", this.setLoaded);
    document.body.appendChild(script);    

  },  
  methods: {

    setLoaded(){
      let id = this.pProductId;
      let cat = this.pCat;
      let userId = this.pUserId;
      let $this = this;
      paypal.Button.render({
        env: 'sandbox', // Or 'production'
        // Set up the payment:
        // 1. Add a payment callback
        payment: function(data, actions) {
          // 2. Make a request to your server
          return actions.request.post('/api/pay/paypal/create-payment?userid='+userId+'&method=paypal&id='+id+'&cat='+cat)
            .then(function(res) {
              // 3. Return res.id from the response
              return res.id;
            });
        },
        // Execute the payment:
        // 1. Add an onAuthorize callback
        onAuthorize: function(data, actions) {
          // 2. Make a request to your server
          return actions.request.post('/api/pay/paypal/execute-payment', {
            paymentID:      data.paymentID,
            payerID:        data.payerID
          })
            .then(function(res) {
              if(res.payer.status == "VERIFIED"){
                $this.$emit('success-pay');
              }
            });
        }
      }, '#paypal-button');
    }
  }  
};
</script>