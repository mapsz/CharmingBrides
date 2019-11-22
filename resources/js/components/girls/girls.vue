<template>
    <div class="container girls-container">
      <!-- Header -->
      <h1 class="py-3">Girls</h1>
      <!-- Search -->
      <girl-search @doSearch="doSearch"/>
      <!-- Girl List -->
      <div class="girls-list row px-lg-0">
        <div 
          v-if="girls.length > 0"
          v-for="girl in girls" 
          class="col-6 col-sm-3 my-2 p-1"
        >
          <div class="card h-100 shadow">
            <a :href="'/girl/'+girl.id">
              <img class="card-img-top " :src="'/'+girl.photo[0]" alt="Juliya">
              <div class="card-body media">
                <div class="align-self-end">
                  <h5 class="card-title name">{{girl.name}}</h5>
                  <span class="card-text">
                    <span class="age">Age: {{girl.age}}</span><br>
                    <span class="city">City: {{girl.location}}</span><br>
                    <span class="id">ID: {{girl.id}}</span>
                  </span>
                </div>
              </div>
            </a>
          </div>
        </div>
        <div v-if="girls.length == 0" class="p-5">
          No Girls found  
        </div>
      </div>
      <!-- Paginator -->      
      <pages :p-page="page" :p-pages="pages" @changePage="changePage"></pages>
    </div>
</template>

<script>
  export default {
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    props:['p-data'],
    data(){
      return {            
        girls:[],
        page:1,
        pages:1,
        search:{},
      }
    },               
    mounted() {
      this.getGirls();
    },
    methods: {
      async getGirls(page = 1){
        let l = this.loading('.girls-container');
        let r = await this.ax('get','/all/girl/search',{page:page,search:this.search,new:this.pData})
        if(!r){
          this.hideLoading(l);
          return false;
        }

        this.girls = JSON.parse(r.data);
        this.pages = JSON.parse(r.settings).pages;
        this.page = page;
        this.hideLoading(l);
      },
      changePage(page){
        this.getGirls(page);
      },
      doSearch(search){
        this.search = search;
        this.getGirls();
      }
    }
  }
</script>
