<template>
    <div class="container">
      <h1 class="modal-title pb-2 text-capitalize">
        <span>Registration</span>
      </h1>   
      <juge-create
        :p-name="pName"  
        :p-inputs="pInputs" 
        :p-route="pRoute" 
        @createSuccess="createSuccess" 
      />
    </div>
</template>



<script>
    export default {
        props:['pInputs','pName','pRoute'],
        methods:{
          createSuccess(a){
            console.log('fff');
            window.location.replace("/profile");
          }
        }
    }
</script>
