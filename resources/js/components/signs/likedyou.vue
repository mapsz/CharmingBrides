<template>
    <div class="container likedyou-container my-3">
      <div class="row">
        <!-- header -->
        <div class="col-6">
          <h1>Liked You</h1>
        </div>
        <!-- link -->
        <div class="col-6">
          <a href="/matched" style="float: right; font-size: 16pt;"><b>Matched</b></a>
        </div>        
      </div>
      <div class="likedyou-list">
        <!-- No likes -->
        <p         
          v-if="(!load) && (likes.length == 0)"
          class="text-center pt-5"
        ><b>No Likes</b></p>
        <!-- likes list -->
        <div class="row">
          <div 
            class="col-2 text-center m-2 p-0"
            v-for="like in likes"
          >
            <a :href="'/girl/'+like.companion.id">
              <img class="w-100" :src="like.companion.photo[0]" alt="">
              <div class="row">
                <span class="col-12">
                  {{like.companion.name}}, {{like.companion.age}}
                </span>    
              </div>    
            </a>   
            <div class="row m-0 border like-buttons">
              <div 
                class="likedyou-dislike col-6 py-2 border-right"
                @click="doLike(like.companion.id,-1)"
              >
                <fa-icon icon="times" />
              </div>
              <div 
                class="likedyou-like col-6 py-2"
                @click="doLike(like.companion.id,1)"
              >
                <fa-icon icon="heart" />
              </div>
            </div>  
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
    import { faHeart } from '@fortawesome/free-solid-svg-icons';
    library.add(faHeart);
    import { faTimes } from '@fortawesome/free-solid-svg-icons';
    library.add(faTimes);


    export default {
      components: {
          FontAwesomeIcon,
      },      
      mixins: [ mMoreAxios, mNotifications, mLoading ],
      data(){
        return {
          likes:[],
          load:true
        }
      },            
      mounted() {
        this.getLikes();
      },
      methods: {
        async getLikes(){
          let l = this.loading('.likedyou-list'); //@@@ loading

          let likes = await this.ax('get', 'get/likedyou');


          // if(matches){
            this.likes = likes;
          // }

          this.load = false;

          this.hideLoading(l);
        },
        async doLike(id,like){
          let l = this.loading('.like-buttons');

          let likes = await this.ax('post', 'like',{toId:id,like:like});

          this.hideLoading(l);

          this.getLikes();
        }
      }
    }
</script>


<style scooped>
  
  .matched-list{
    min-height: 150px;
  }

  .likedyou-like{
    color:#f851eb;
    cursor:pointer;
  }
  .likedyou-dislike{
    color:#9E9E9E;
    cursor:pointer;
  }
  .likedyou-like:hover{
    color:#fc00e8;
  }
  .likedyou-dislike:hover{
    color:#747373;
  }
</style>