<template>
    <div class="letters container my-4">
      <div class="row">
        <!-- Companion list -->
        <div class="companions col-3">
            <letter-companions-component 
              :p-companions="companions"
              :p-active-companion="activeCompanion"
              @set-active-companion="setActiveCompanion"
            />
        </div>
        <!-- Letter list -->
        <div class="letter-list col-9">
          <div v-if="activeCompanion" @click="sendLetter();" class="send-letter action-item">
            <fa-icon :icon="['far', 'envelope']" class="fa" />
            Send letter
          </div>
          <letter-list-component 
            :p-letters = "activeLetters"
            :p-user = "pUser"
            @pay-letter="payLetter"
          />
        </div>
      </div>

      <letter-buy-component 
        v-if="pay"
        :p-letter="pay"
        @closePay="pay = false"
        @successPay="successPay"
      />
      <message-send-component         
        @closeLetter="send = false" 
        @send-success="sendSuccess()"
        :p-user="activeCompanion" 
        :p-from="pUser.id" 
        :p-letter="send" 
      />      
    </div>
</template>

<script>
    export default {
        props:['p-user'],
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            //Companions
            companions:[],
            activeCompanion:false,
            //Letters
            letters:[],
            activeLetters:false,
            send:false,
            //pay
            pay:false,
          }
        },
        watch: {
          pUser: {
            deep:true,
            handler:function(){
              this.refreshCompanions();
              this.refreshLetters()
            },
          }, 
        }, 
        async mounted() {

          let l = this.showLoading('.letters');

          if(this.pUser){
            //Get companions
            await this.getCompanions();

            //Set active companion
            if(this.companions[0] != undefined){
              this.setActiveCompanion(this.companions[0]);
            }            
          }

          this.hideLoading(l);
        },
        methods: {
          //Companions
          async getCompanions(){
            let l = this.showLoading('.companions');

            let companions = await this.ax('get', 'letter/get/companions',{'userId':this.pUser.id});

            this.hideLoading(l);
            if(!companions) return false;

            //set companions
            this.companions = companions;

            return true;
          },
          async setActiveCompanion(companion){
            //Set active companion
            this.activeCompanion = companion;

            this.setActiveLetters(companion.id);
            return true;            
          },
          async refreshCompanions(){
            this.companions = []; //@@@
            this.activeCompanion = false; //@@@            
            await this.getCompanions();
            //Set active companion
            if(this.companions[0] != undefined){
              this.setActiveCompanion(this.companions[0]);
            }               
          },
          //Letters
          async getLetters(userId,companionId){
            //Show loading
            let l = this.showLoading('.letter-list');

            //Get letters
            let letters = await this.ax('get', 'letter/get',{'userId':userId,'companionId':companionId});

            //Error            
            if(!letters){
              this.hideLoading(l);
              return false;
            } 

            //Add talkers
            letters = {
              'userId' : userId,
              'companionId': companionId,
              'letters': letters,
            }

            //add letters
            this.letters.push(letters);

            this.hideLoading(l);
            return letters;
          },
          lettersExists(userId,companionId){

            //Get user letters
            let userLetters = this.letters.filter(x => x.userId == userId);

            //check userser letter exists
            if(userLetters.lenght < 1)
              return false

            //Search required letters
            let searchLetter = false;
            $.each(userLetters, function(i, letter) {
              if(letter.companionId == companionId){
                searchLetter = i;
              }
            });

            return searchLetter;
          },
          async setActiveLetters(companionId){
            let l = this.showLoading('.letter-list');

            //Set letters
            let letters = this.lettersExists(this.pUser.id,companionId);

            //Exists
            if(letters !== false){
              this.activeLetters = this.letters[letters].letters;
              this.hideLoading(l);return true;              
            }

            //not existing
            let activeLetters = await this.getLetters(this.pUser.id,companionId);

            if(activeLetters.letters != undefined){
              this.activeLetters = activeLetters.letters;
            }

            this.hideLoading(l);return true;
          },
          payLetter(letterId){
            //
            this.pay = letterId;
          },
          successPay(letter){
            $.each(this.letters, (i, v) => {
              $.each(v.letters, (j, l) => {
                if(l.id == letter.id){
                  this.letters[i].letters[j].body  = letter.body;
                  this.letters[i].letters[j].payed = true;
                }
              });
            });

            this.setActiveCompanion(this.activeCompanion);
          },
          sendLetter(){
            //
            this.send = true;
          },
          refreshLetters(){            
            this.letters = []; //@@@
            this.activeLetters = []; //@@@            
            this.setActiveLetters(this.activeCompanion.id);
          },
          sendSuccess(){            
            this.send = false;
            this.refreshLetters();
          }
        }
    }
</script>


<style scoped>

  .send-letter{

    cursor:pointer;
    border:1px solid black;

  }



</style>