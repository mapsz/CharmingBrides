<template>
  <div class="container">

    <div v-if="pUserIsMan >= 3" class="row pt-3 girl-admin-info">
      <div class="col-12 my-3">
        <div class="row">
          <h2 class="col-12">Admin Info</h2>    
        </div>
        <div class="row">
          <!-- passport -->
          <div class="col-2">
            <div class="girl-passport">
              <img class="w-100" :src="'/'+girl.passport[0]" alt="Passport">
            </div>  
          </div>
          <!-- for admin info -->
          <div class="col-3">
            <!-- name -->
            <div class="row">
              <div class="col-5">name:</div>
              <div class="col-7" style="color: #bf005a;"> {{girl.forAdminName}}</div> 
            </div>
            <!-- surname-->
            <div class="row">
              <div class="col-5">surname:</div>
              <div class="col-7" style="color: #bf005a;"> {{girl.forAdminSurname}}</div> 
            </div>
            <!-- fathername  -->
            <div class="row">
              <div class="col-5">fathername:</div>
              <div class="col-7" style="color: #bf005a;"> {{girl.forAdminFathersName}}</div> 
            </div>
            <!-- phone number  -->
            <div class="row">
              <div class="col-5">number:</div>
              <div class="col-7" style="color: #bf005a;"> {{girl.forAdminPhoneNumber}}</div> 
            </div>
            <!-- email  -->
            <div class="row">
              <div class="col-5">email:</div>
              <div class="col-7" style="color: #bf005a;"> {{girl.email}}</div> 
            </div>               
          </div>
          <!-- letter -->
          <div class="col-7">
            <!-- subject -->
            <div class="row">
              <div class="col" style="color: #bf005a;"><b> {{girl.firstLetterSubject}}</b></div> 
            </div>
            <!-- letter-->            
            <div class="row">
              <div class="col" style="color: #bf005a;"> {{girl.firstLetter}}</div> 
            </div>         
          </div>
        </div>
      </div>
    </div>

    <div class="row my-3">
      <div class="col-3">
        <div class="girl-main-image">
          <img class="w-100" :src="'/'+mainPhoto" alt="Juliya">
        </div>
        <div v-if="pAuth" class="girl-more-info">
          <div class="girl-more-info-rows text-capitalize">
            <div v-for="(v,k) in moreInfo" class="row">
              <div class="col-5">
                {{k}}
              </div>
              <div class="col-7" style="color: #bf005a;">
                {{v}}
              </div>
            </div>
          </div>
        </div>        
      </div>
      <div class="col-9" style="color: #bf005a;">
        <h1 class="" style="color:#740f0f">{{girl.name}}<span> ({{girl.id}})</span></h1>
        <p v-if="girl.location">From {{girl.location}}</p>
        <p v-if="girl.age">{{girl.age}} Years old</p>

        <div v-if="pAuth">
          <!-- loged in -->

            <!-- Gallery -->
            <div class="row">
              <gallery :images="images" :index="index" @close="index = null"></gallery>
              <div
                class="girl-image col-3 m-0"
                v-for="(image, imageIndex) in images"
                :key="imageIndex"
                @click="index = imageIndex"
                :style="{ backgroundImage: 'url(' + image + ')', height: '200px' }"
                style="background-position: top;"
              ></div>
            </div>

            <!-- Man options -->
            <div v-if="pUserIsMan == 1" class="row text-center font-weight-bold">
              <div @click="sendLetter();" class="action-item col-4 p-3">
                <fa-icon :icon="['far', 'envelope']" class="fa-5x d-block mx-auto" />
                Send me a letter
              </div>
              <div @click="sendLike();"class="action-item send-kiss col-4 p-3">
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

            <!-- info -->
            <div v-if="pAuth" class="row girl-info">
              {{girl.info}}
            </div>
        </div>
        <div v-else>
          <!-- not loged in -->
          <!-- @@@ buttons -->
          <b>Please login or register to see all the photos and profile of this lady</b>
        </div>
      </div>
    </div>

  <!-- Letter -->
  <message-send-component @closeLetter="letter = false" :p-user="girl" :p-letter="letter" />

  </div>
</template>

<script>

    // Vue gallery
    import VueGallery from 'vue-gallery';

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
      components: {
        'gallery': VueGallery
      },      
      mixins: [ mMoreAxios, mNotifications, mLoading ],
      props:['p-girl','p-auth','p-user-is-man'],
      data(){
        return {
          assets:assets,     
          index: null,         
          girl:false,
          letter:false,
          mainPhoto:'',
        }
      }, 
      computed:{
        moreInfo:function(){
          let mi = {
            'Birth'         : this.girl.birth,
            'Height'        : this.girl.height,
            'Weight'        : this.girl.weight,
            'Hair'          : this.girl.hair,
            'Eyes'          : this.girl.eyes,
            'Religion'      : this.girl.religion,
            'Education'     : this.girl.education,
            'Profession'    : this.girl.profession,
            'Maritial'      : this.girl.maritial,
            'Children'      : this.girl.children,
            'Smoking'       : this.girl.smoking,
            'Alcohol'       : this.girl.alcohol,
            'English'       : this.girl.english,
            'Languages'     : this.girl.languages,
            'Preffer Age'   : this.prefferAge,
          }

          $.each(mi, (index, val) => {
             if(val == undefined || val == "" || val == null){
              delete(mi[index]);
             }
          });

          return mi;
        },
        prefferAge:function(){
          let from, to;
          if(!this.girl.prefferFrom && !this.girl.prefferTo)
            return false;

          //From
          if(!this.girl.prefferFrom)
            from = "18";
          else{
            from = this.girl.prefferFrom;
          }

          //To
          if(!this.girl.prefferTo)
            to = "99";
          else{
            to = this.girl.prefferTo;
          }

          return from +" - "+to;
        },
        images:function(){
          let images = []; 
          $.each(this.girl.photo, function(index, val) {
             images.push(assets+'/'+val);
          });
          return images;
        }
      },        
      mounted() {
        this.girl = JSON.parse(this.pGirl);
        this.mainPhoto = this.girl.photo[0];
        this.girl.photo.shift();

      },
      methods:{
        sendLetter(){
          this.letter = true;
        },
        async sendLike(){
          let l = this.loading('.send-kiss');

          let likes = await this.ax('post', '/like',{toId:this.girl.id,like:1});

          this.hideLoading(l);

          this.showSuccess('Kiss Send!');

          
        }
      }
        
    }

</script>

<style scoped>

  .girl-image {
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    border: 1px solid #ebebeb;
    margin: 5px;
    cursor:pointer;
  }

  .girl-admin-info {
      background-color: #ffc8004a;
  }

</style>

<style>
  
  .action-item{
    background: radial-gradient(50% 50%, #bf005a38, #f8fafc); 
    cursor:pointer;
  }
  .action-item:hover{
    color: #740f0f;
  }

</style>