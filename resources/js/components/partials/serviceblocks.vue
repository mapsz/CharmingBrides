<template>
  <div class="container-fluid servicesBlocks">
    <div class="row">
      <!-- Live Assistant -->
      <div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
        <a href="/news">
          <div class="card">          
            <div class="card-body p-1 media">
              <fa-icon icon="newspaper" class="pl-1" style="height:128px; width: 100px;"/>
              <div class="card-text align-self-center">
                <h5 class="card-title">News</h5>            
                <p class="card-text">Be aware of all the latest news of the agency!</p>
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
              <fa-icon icon="lock" class="pl-1" style="height:128px; width: 100px;"/>
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
            <fa-icon icon="gift" class="pl-1" style="height:128px; width: 100px; color: #960046;"/>
            <div class="card-text align-self-center">
              <h5 class="card-title" style="color: #960046;">Our Services</h5>            
                <ul style="    padding-left: 40px;">
                  <li><a href="/coming/soon">All services</a></li>
                  <li><a href="/memberships">Membership</a></li>
                  <li><a href="/coming/soon">Gifts</a></li>
                  <li><a href="/coming/soon">Flowers</a></li>
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
              <fa-icon icon="heart" class="pl-1" style="height:128px; width: 100px;"/>
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

    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    // Vue.component('fa-icon', FontAwesomeIcon); 
    import { library } from '@fortawesome/fontawesome-svg-core';    
    Vue.config.productionTip = false;
    //icons
    import { faNewspaper } from '@fortawesome/free-solid-svg-icons';
    library.add(faNewspaper);
    import { faLock } from '@fortawesome/free-solid-svg-icons';
    library.add(faLock);
    import { faGift } from '@fortawesome/free-solid-svg-icons';
    library.add(faGift);
    import { faHeart } from '@fortawesome/free-solid-svg-icons';
    library.add(faHeart);

    export default {}
</script>
