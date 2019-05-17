<template>
    <div class="container my-4">
      <div class="row">
        <div class="col-3">
          <div class="list-group">
            <div 
              v-for="letter in letters"
              class="user-item list-group-item list-group-item-action"
              v-bind:class="{'item-active' : active == letter.companion.user_id}"
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
        <div class="col-9">
          <ul>
            <li v-for="currentLetter in currentLetters">
              from:{{currentLetter.user_id}}  To:{{currentLetter.to_user_id}}<br>
              <b>{{currentLetter.subject}}</b><br>
              <button v-if="currentLetter.payed == false">Read Letter!</button>
              {{currentLetter.body}}
              <hr>
            </li>

          </ul>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        data(){
          return {
            assets:assets,
            letters:[],
            active:false,
            currentLetters:[],
          }
        },
        mounted() {
            this.getLetters();

            console.log(assets);
        },
        methods: {
          async getLetters(){

              var r = await axios.get('/letter/user')
                .then((r) => {

                  console.log(r.data);        
                    if(!r.data.error){
                        return r.data;                           
                    }else{
                        return [];
                    }
                })
                .catch((r) => {this.debug(r);return false;});

            this.letters = r;
          },
          setActive(active){
            this.active = active;
            this.currentLetters = this.letters.filter(x => x.companion.user_id == active)[0].letters;

            console.log(this.currentLetters);
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