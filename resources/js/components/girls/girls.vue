<template>
    <div class="container">
      <!-- Header -->
      <h1 class="py-3">Girls</h1>
      <!-- Search -->
      <girl-search @searchSuccess="updateData"/>
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
      <div v-if="setting.pages > 1" class="row d-flex justify-content-center">
        <paginate        
          :page-count="setting.pages"
          :value="page"
          :container-class="'pagination'"
          :page-class="'page-item'"
          :page-link-class="'page-link'"
          :prev-class="'page-item'"
          :prev-link-class="'page-link'"
          :next-class="'page-item'"
          :next-link-class="'page-link'"
          :click-handler="pageHandler"
        >
        </paginate>
      </div>

    </div>
</template>

<script>
    export default {
        props:['p-girls','p-settings'],
        data(){
          return {
            page:1,
            girls:JSON.parse(this.pGirls),
            setting:JSON.parse(this.pSettings),
          }
        },               
        mounted() {
          this.getCurrentPage();
        },
        methods: {
          getCurrentPage(){
            let qs = queryString.parse(location.search);
            if(qs.page !== undefined){
              this.page = parseInt(qs.page);
            }
          },
          pageHandler(page){
            location.replace(location.origin + location.pathname + '?page=' + page);
          },
          updateData(data){
            this.girls = JSON.parse(data.girls);
            this.setting = JSON.parse(data.settings);
          }
        }
    }
</script>
