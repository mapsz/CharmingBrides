<template>
    <div class="container my-4">
      <div class="row">
        <!-- Companion list -->
        <div class="col-3">
          <div class="list-group">
            <!-- Companion -->
            <div 
              v-for="letter in letters" :key='letter.id'
              class="user-item list-group-item list-group-item-action"
              v-bind:class="{'item-active' : activeCompanion == letter.companion.user_id}"
              @click="setActive(letter.companion.user_id)"
            >
              <div class="item-wrapper row">
                <div class="img-wrapper col-4 text-center">
                  <img class="" :src="assets+'/media/gallery/'+letter.companion.user_id+'_0.jpg'" :alt="letter.companion.name">
                </div>
                <div class="info-wrapper col-8">
                  {{letter.companion.name}} 
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Letter list -->
        <div class="col-9">
          <ul>
            <li v-for="currentLetter in currentLetters" :key='currentLetter.id'>
              <!-- Letter -->
              from:{{currentLetter.user_id}}  To:{{currentLetter.to_user_id}}<br>
              {{currentLetter.created_at}}<br>
              <b>{{currentLetter.subject}}</b><br>
              <button 
                  v-if="currentLetter.payed == false"
                  @click="setPay(currentLetter)"
              >
                Read Letter!
              </button>
              <div v-else class="message">
                  {{currentLetter.body}}
              </div>
              <hr>
            </li>
          </ul>
        </div>
      </div>
      <letter-buy-component 
        v-if="pay"
        :p-letter="pay"
        @closePay="pay = false"
        @successPay="successPay"
      />
    </div>
</template>

<script>
    export default {
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            assets:assets,
            letters:[],
            companions:[],
            activeCompanion:false,
            currentLetters:[],
            pay:false,
          }
        },
        async mounted() {
            this.letters = await this.getLetters();   
            if(this.letters[0] != undefined){
              this.setActive(this.letters[0].companion.user_id);
            }      
            console.log(assets);
        },
        methods: {
          async getLetters(){
            var r = await this.ax('get','/letter/user');   
            return r;
          },
          setActive(active){
            this.currentLetters = this.letters.filter(x => x.companion.user_id == active)[0].letters;
            this.activeCompanion = active;
          },
          setPay(letter){
            //
            this.pay = letter;
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
          }

        }
    }
</script>


<style scoped>

  .user-item .img-wrapper{
    height:40px;
  }
  
  .user-item img{
    height:100%;
  }

  .user-item{
    cursor:pointer;
  }

  .item-active{
    background-color: #bf005a;
    color: white;
  }


</style>