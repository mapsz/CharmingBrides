<template>
  <div class="container-fluid servicesBlocks">
    <div class="row">
      <!-- Live Assistant -->
      <div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
        <a href="/news">
          <div class="card">          
            <div class="card-body p-1 media">
              <img class="float-left pr-2 align-self-center" src="/img/services/live_assistant.png" alt="Live Assistant">
              <div class="card-text align-self-center">
                <h5 class="card-title">Live Assistant</h5>            
                <p class="card-text">Our Live Assistant will help you to get best of our service</p>
              </div>
            </div>
          </div>  
        </a>  
      </div>
      <!-- Highest Security -->
      <div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
        <a href="/anti/scam">
          <div class="card">          
            <div class="card-body p-1 media">
              <img class="float-left pr-2 align-self-center" src="/img/services/security.png" alt="Live Assistant">
              <div class="card-text align-self-center">
                <h5 class="card-title">Highest Security</h5>            
                <p class="card-text">Learn more about our Security and Anti-SCAM programs we support</p>
              </div>
            </div>
          </div>
        </a>  
      </div>
      <!-- Our services -->
      <div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
        <div class="card">          
          <div class="card-body p-1 media">
            <img class="float-left pr-2 align-self-center" src="/img/services/services.png" alt="Live Assistant">
            <div class="card-text align-self-center">
              <h5 class="card-title">Our Services</h5>            
                <ul>
                  <li><a href="">All services</a></li>
                  <li><a href="">Membership</a></li>
                  <li><a href="">Gifts</a></li>
                  <li><a href="">Flowers</a></li>
                  <li><a href="">Information</a></li>
                </ul>   
            </div>
          </div>
        </div>  
      </div>
      <!-- Romantic tour -->
      <div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
        <a :href="'romantic/tour'">
          <div class="card">          
            <div class="card-body p-1 media">
              <img class="float-left pr-2 align-self-center" src="/img/services/romantic_tour.png" alt="Live Assistant">
              <div class="card-text align-self-center">
                <h5 class="card-title">Romantic Tour</h5>           
                <p class="card-text">Learn more about Romantic Tour to Odessa</p>
              </div>
            </div>
          </div>
        </a>  
      </div>
    </div>
  </div>
</template>

<script>
    export default {}
</script>
