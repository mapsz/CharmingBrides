<template>
  <div class="container">
    <div class="row my-3">
      <div class="col-3">
        <img class="w-100" :src="'/public/media/gallery/'+girl.id+'_0.jpg'" alt="Juliya">
      </div>
      <div class="col-9" style="color: #bf005a;">
        <h1 class="" style="color:#740f0f">{{girl.name}}, {{girl.age}}</h1>
        <p>From {{girl.location}}</p>

        <div v-if="prop_auth">
          <!-- loged in -->
            <div class="row text-center font-weight-bold">
              <div @click="sendLetter();" class="action-item col-4 p-3">
                <fa-icon :icon="['far', 'envelope']" class="fa-5x d-block mx-auto" />
                Send me a letter
              </div>
              <div class="action-item col-4 p-3">
                <fa-icon :icon="['far', 'kiss-wink-heart']" class="far fa-5x d-block mx-auto"/>
                Send me a kiss
              </div>
              <div class="action-item col-4 p-3">
                <fa-icon :icon="['fab', 'skype']" class="far fa-5x d-block mx-auto"/>
                Getting a girl's skype contact
              </div>
              <div class="action-item col-4 p-3">
                <fa-icon :icon="['fas', 'gifts']" class="far fa-5x d-block mx-auto"/>
                Gifts ideas
              </div>
              <div class="action-item col-4 p-3">
                <fa-icon :icon="['fas', 'gift']" class="far fa-5x d-block mx-auto"/>
                Send virtual gift
              </div>
              <div class="action-item col-4 p-3">
                <fa-icon :icon="['far', 'star']" class="far fa-5x d-block mx-auto"/>
                Add {{girl.name}} to favorites
              </div>
            </div>

        </div>
        <div v-else>
          <!-- not loged in -->
          <b>Please login or register to see all the photos and profile of this lady</b>
        </div>
      </div>
    </div>

  <!-- Letter -->
  <message-send-component :p-user="girl" :p-letter="letter" />

  </div>
</template>

<script>

    // Font Awsome   
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    Vue.component('fa-icon', FontAwesomeIcon); 
    import { library } from '@fortawesome/fontawesome-svg-core';    
    Vue.config.productionTip = false;
    //icons
    import { faEnvelope as fasEnvelope} from '@fortawesome/free-solid-svg-icons';
    import { faEnvelope as farEnvelope } from '@fortawesome/free-regular-svg-icons';
    library.add(fasEnvelope,farEnvelope);

    import { faKissWinkHeart} from '@fortawesome/free-regular-svg-icons';
    library.add(faKissWinkHeart);

    import { faSkype} from '@fortawesome/free-brands-svg-icons';
    library.add(faSkype);

    import { faGifts} from '@fortawesome/free-solid-svg-icons';
    library.add(faGifts);
    import { faGift} from '@fortawesome/free-solid-svg-icons';
    library.add(faGift);
    import { faStar} from '@fortawesome/free-regular-svg-icons';
    library.add(faStar);


    export default {
        props:['prop_girl','prop_auth'],
        data(){
            return {
                girl:false,
                letter:false,
            }
        }, 
        mounted() {
          this.girl = JSON.parse(this.prop_girl);
        },
        methods:{
          sendLetter(){
            this.letter = true;
          }
        }
        
    }

</script>


<style>
  .action-item{
    background: radial-gradient(50% 50%, #bf005a38, #f8fafc); 
    cursor:pointer;
  }
  .action-item:hover{
    color: #740f0f;
  }
  



</style>