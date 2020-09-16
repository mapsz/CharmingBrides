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

          <div style="display: flex;justify-content: center;margin-top: 20px;">
            <ul  class="pagination">
              <li v-if="companionPage > 1" class="page-item" style="cursor: pointer;">
                <a @click.prevent="companionPage--" class="page-link">Prev</a>
              </li> 
              <li style="cursor: pointer;"> 
                <a @click.prevent="companionPage++" class="page-link">Next</a>
              </li>
            </ul>
          </div>
            
        </div>

        <!-- Letter list -->
        <div class="letter-list col-9">
          <div class="row" style="border-bottom: 2px solid #bf005a73; padding-bottom:10px;">
            <div class="col-3 media">
              <div class="align-self-center">
                <button 
                  v-if="activeCompanion" 
                  @click="sendLetter();" 
                  class="btn send-letter action-item"
                  style="background-color: #ffff006b;"
                >
                  <fa-icon :icon="['far', 'envelope']" class="fa" />
                  Send letter
                </button>
              </div>
            </div>
            <div class="col-9">              
              <letter-companion-info :p-companion="activeCompanion" />
            </div>
          </div>
          <letter-list-component 
            :p-letters = "activeLetters"
            :p-user = "pUser"
            :p-user-from = "userFrom"
            :p-companion = "activeCompanion"
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
        :p-from="userFrom.id" 
        :p-letter="send" 
      />      
    </div>
</template>

<script>
    export default {
        props:['p-user','p-user-from','p-user-to'],
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            userFrom:false,
            //Companions
            companionPage:1,
            with:false,
            companions:[],
            activeCompanion:false,
            //Letters
            letters:[],
            activeLetters:false,
            send:false,
            //pay
            pay:false,
            //query companion
            queryCompanion:this.getCompanionFromQuery(),
          }
        },
        watch: {
          companionPage: function(){
            this.refreshCompanions();
          },
          pUserFrom: {
            deep:true,
            handler:function(){
              this.userFrom = this.pUserFrom;
              this.refreshCompanions();
              this.refreshLetters();
            },
          }, 
          pUserTo: {
            deep:true,
            handler:function(){
              this.setActiveCompanion(this.pUserTo);
            },
          },           
        }, 
        async mounted() {
          let l = this.showLoading('.letters');

          //check pre with
          let queryString = this.getUrlVars();
          if(queryString.with !== undefined){
            this.with = queryString.with;
          }

          //set user from
          if(this.pUserFrom == undefined) this.userFrom = this.pUser;
          else                            this.userFrom = this.pUserFrom;

          //User preload       
          if(this.userFrom){
            await this.refreshCompanions();
          }

          this.hideLoading(l);
        },
        methods: {
          //Companions
          async getCompanions(){
            let l = this.showLoading('.companions');

            let companions = await this.ax('get', 'letter/get/companions',{'userId':this.userFrom.id,'companion_page':this.companionPage});

            this.hideLoading(l);
            
            if(!companions) return false;

            //set companions
            this.companions = companions;

            return true;
          },
          getCompanionFromQuery(){
            let queryString = this.getUrlVars();
            if(queryString.companion !== undefined){
              return queryString.companion;
            } 

            return false;
          },
          getUrlVars(){
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
          },          
          async setActiveCompanion(companion){
            //Companion not object
            if($.type(companion) != 'object'){
              let c;
              //search companion
              c = this.companions.find(x => x.id == companion);
              //Check companion exists
              if(c == undefined){
                //Get companion
                ///
                c = await this.ax('get','/letter/get/companion',{id:companion});

                if(!c) return false;

                this.companions = [c].concat(this.companions);
                
              }    
              companion = c;
            }

            //Set active companion
            this.activeCompanion = companion;

            this.setActiveLetters(companion.id);
            return true;            
          },
          async refreshCompanions(){
            let l = this.showLoading('.companions');
            this.companions = []; //@@@
            this.activeCompanion = false; //@@@            
            await this.getCompanions();           
            //Set active companion
            if(this.with){
              await this.setActiveCompanion(this.with);
              this.with = false;
            }          

            if(this.companions[0] == undefined && this.activeCompanion == false){
              await this.setActiveCompanion(this.queryCompanion);
            }
            
            if(this.companions[0] != undefined && this.activeCompanion == false){              
              //set from query
              if(this.queryCompanion){
                await this.setActiveCompanion(this.queryCompanion);
              }else{
                await this.setActiveCompanion(this.companions[0]);
              }
              
            }     
            this.hideLoading(l);
            return true;               
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

            //read letters
            this.readLetters(companionId);

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
            let letters = this.lettersExists(this.userFrom.id,companionId);

            //Exists
            if(letters !== false){
              this.activeLetters = this.letters[letters].letters;
              this.hideLoading(l);return true;              
            }

            //not existing
            let activeLetters = await this.getLetters(this.userFrom.id,companionId);

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
            // $.each(this.letters, (i, v) => {
            //   $.each(v.letters, (j, l) => {
            //     if(l.id == letter.id){
            //       this.letters[i].letters[j].body   = letter.body;
            //       this.letters[i].letters[j].photos = true;
            //       this.letters[i].letters[j].payed  = true;
            //     }
            //   });
            // });

            this.refreshLetters();

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
          },
          getUrlVars(){
              var vars = [], hash;
              var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
              for(var i = 0; i < hashes.length; i++)
              {
                  hash = hashes[i].split('=');
                  vars.push(hash[0]);
                  vars[hash[0]] = hash[1];
              }
              return vars;
          },       
          async readLetters(companionId){
            let i = this.companions.findIndex(x => x.id == companionId);

            let letters = await this.ax('post', 'letter/read',{'user_id':this.userFrom.id,'companion_id':companionId});

            this.activeCompanion.read = true;
            this.companions[i].read = true;
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