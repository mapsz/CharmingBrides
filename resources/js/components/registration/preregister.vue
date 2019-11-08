<template>
    <div class="container">
      <div class="text-center mb-5">
        <h1>Registration</h1>
      </div>
      <div class="row text-center pre-regiter-buttons">
        <div class="col-6">
          <div class="button female mx-4"
            @click='redirect("/registration/girl")'
          >
            <fa-icon icon="venus" class="icon"/>
            <p>FEMALE</p>
          </div>
        </div>
        <div class="col-6">
          <div class="button male mx-4"
            @click='redirect("/registration/man")'
          >
            <fa-icon icon="mars" class="icon"/>
            <p>MALE</p>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  // Font Awsome   
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  // Vue.component('fa-icon', FontAwesomeIcon); 
  import { library } from '@fortawesome/fontawesome-svg-core';    
  Vue.config.productionTip = false;
  //icons
  import { faVenus } from '@fortawesome/free-solid-svg-icons';
  library.add(faVenus);
  import { faMars } from '@fortawesome/free-solid-svg-icons';
  library.add(faMars);

  export default {   
    methods: {
      async redirect(link){
        window.location.replace(link);
      }
    }   
  }
</script>

<style scooped>
  
  .pre-regiter-buttons{
    font-size: 100pt;
  }
  .pre-regiter-buttons p {
    font-size: 24pt;
  }
  .pre-regiter-buttons .button {
    cursor:pointer;
  }
  .pre-regiter-buttons .female {
    color: #ff72ff;
  }
  .pre-regiter-buttons .male {
    color: #5297fe;
  }
  .pre-regiter-buttons .female:hover {
    color: #ff00ff;
  }
  .pre-regiter-buttons .male:hover {
    color: #0056d7;
  }
  
</style>
