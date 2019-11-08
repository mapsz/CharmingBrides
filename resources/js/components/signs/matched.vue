<template>
    <div class="container matched-container my-3">
      <div class="row">
        <!-- header -->
        <div class="col-6">
          <h1>Matched</h1>
        </div>
        <!-- link -->
        <div class="col-6">
          <a href="/likedyou" style="float: right; font-size: 16pt;"><b>Liked you</b></a>
        </div>        
      </div>
      <div class="matched-list">
        <!-- No matches -->
        <p         
          v-if="(!load) && (matches.length == 0)"
          class="text-center pt-5"
        ><b>No Matches</b></p>
        <!-- matchlist -->
        <div class="row">
          <div 
            class="col-2 text-center my-2"
            v-for="match in matches"
          >
            <a :href="'/girl/'+match.companion.id">
              <img class="w-100" :src="match.companion.photo[0]" alt="">
              <span class="">
                {{match.companion.name}}, {{match.companion.age}}
              </span>
            </a>
            <!-- {{match.name}} -->

          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
      mixins: [ mMoreAxios, mNotifications, mLoading ],
      data(){
        return {
          matches: [],
          load:true,
        }
      },            
      mounted() {
        this.getMatches();
      },
      methods: {
        async getMatches(){
          // let l = this.loading('.matched-list'); //@@@ loading

          let matches = await this.ax('get', 'matches');

          console.log(matches);

          // if(matches){
            this.matches = matches;
          // }

          this.load = false;
          
        }
      }
    }
</script>


<style scooped>
  
  .matched-list{
    min-height: 150px;
  }


</style>