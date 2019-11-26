<template>
  <div class="container-fluid mailer-container p-0">
    <h1 class="m-4">Mailer</h1>

    <!-- Search Users-->
    <div class="search-container">
      <div class="col-12 text-center search-container">
        <h3>Search</h3>
      </div>
      <div class="row m-0 p-0">
        <div class="col-6  girls-search-container">
          <div class="container-fluid py-3 border">
            <div class="row  m-0 p-0">
              <h3 class="text-center">From Girls</h3>
            </div>
            <!-- Search -->
            <div class="row  m-0 p-0">
              <juge-search
                :p-search="JSON.parse(pData).girlSearch"
                :p-param-route="'/parametrs/girl'"
                @doSearch="girlSearch"
              ></juge-search>
            </div>
            <!-- Serach List -->
            <div class="row m-0 p-0">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col">
                      <fa-icon 
                        icon="plus" 
                      />
                    </th>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Age</th>
                    <th scope="col">Location</th>
                    <th scope="col">Agent</th>
                    <th scope="col">Created At</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="girl in searchGirls">
                    <th>
                      <fa-icon 
                      @click="addFromList(girl.id)" 
                      icon="plus" 
                      class="pl-1 text-success"
                      style="cursor:pointer"/>
                    </th>
                    <th>{{girl.id}}</th>
                    <td>{{girl.name}}{{(girl.forAdminSurname)?' '+girl.forAdminSurname:""}}</td>
                    <td>{{girl.birth}}</td>
                    <td>{{girl.location}}</td>
                    <td>{{girl.agent}}</td>
                    <td>{{girl.created_at}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Paginator -->      
            <pages :p-page="girlsPage" :p-pages="girlsPages" @changePage="girlChangePage"></pages>  
            <!-- add button -->
            <button @click="addFromSearchGirls()" class="btn btn-sm btn-success"><fa-icon icon="plus" class="pl-1"/> Add all from search list</button>          
          </div>
        </div>
        <div v-if="searchMenShow" class="col-6 men-container men-search-container">
          <div class="container-fluid py-3 border">
            <h3 class="text-center">To Men</h3>
            <!-- Search -->
            <div class="row m-0 p-0">
              <juge-search
                :p-search="JSON.parse(pData).manSearch"
                :p-param-route="'/parametrs/men'"
                @doSearch="menSearch"
              ></juge-search>
            </div>
            <!-- Serach List -->
            <div class="row m-0 p-0">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col">Add</th>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Age</th>
                    <th scope="col">Country</th>
                    <th scope="col">Created At</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="men in searchMen">
                    <th>
                      <fa-icon 
                      @click="addToList(men.id)" 
                      icon="plus" 
                      class="pl-1 text-success"
                      style="cursor:pointer"/>
                    </th>
                    <th>{{men.id}}</th>
                    <td>{{men.name}}{{(men.surname)?' '+men.surname:""}}</td>
                    <td>{{men.birth}}</td>
                    <td>{{men.country}}</td>
                    <td>{{men.created_at}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Paginator -->      
            <pages :p-page="menPage" :p-pages="menPages" @changePage="manChangePage"></pages>  
            <!-- add button -->
            <button @click="addFromSearchMen()" class="btn btn-sm btn-success"><fa-icon icon="plus" class="pl-1"/> 
              Add all from search list
            </button>
            <button @click="addAllMen()" class="btn btn-sm ml-2 btn-primary"><fa-icon icon="plus" class="pl-1"/>
              Add all Men
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Mail list -->
    <div class="mail-list-container">
      <div class="col-12 text-center mt-3">
        <h3>Mail List</h3>
      </div>
      <div class="row m-0 p-0">
        <div class="col-6">
          <div class="container-fluid py-3 border">
            <div class="row m-0 p-0">
              <h3>From List</h3>
            </div>
            <div class="row m-0 p-0">
              <span
                v-for="u in fromList"
              > 
                {{u.name}}({{u.id}}).
              </span>
            </div>
            <div class="row m-0 p-0">
              <b class="col-12">Total Girls:{{fromList.length}}</b>
              <button @click="removeListGirls()" class="btn btn-sm btn-danger"><fa-icon icon="plus" class="pl-1"/> Remove</button>
            </div>
          </div>
        </div>
        <div class="col-6 men-container">
          <div class="container-fluid py-3 border">
            <div class="row m-0 p-0">
              <h3>To List</h3>
            </div>
            <div class="row m-0 p-0">
              <span v-for="u in toList"> 
                {{u.name}}({{u.id}}).
              </span>
              <span v-if="toAllMen">
                <b>ALL MEN SELECTED</b>
              </span>
            </div>          
            <div class="row m-0 p-0">
              <b class="col-12">Total Men:{{(toListTotal) ? toListTotal : toList.length}}</b>
              <button @click="removeListMen()" class="btn btn-sm btn-danger"><fa-icon icon="plus" class="pl-1"/> Remove</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Make Maile --> 
    <div class="row justify-content-center m-0 my-4">
      <button @click="createMailer('letters')" class="btn" style="
            color: #000;
            background-color: #ddc200;
            border-color: #000000;
        ">
        <b><fa-icon icon="envelope" class="pr-1"/>Sent Letters</b>
      </button>          
      <button @click="createMailer('signs')" class="btn btn-success ml-2" style="
              color: #000;
              background-color: #ba0078;
              border-color: #000000;
        ">
        <b><fa-icon icon="heart" class="pr-1"/>Sent Signs</b>
      </button>          
    </div>

    <!-- Mailer List -->
    <mailer-list></mailer-list>    
  </div>
</template>

<script>
  // Font Awsome   
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  // Vue.component('fa-icon', FontAwesomeIcon); 
  import { library } from '@fortawesome/fontawesome-svg-core';    
  Vue.config.productionTip = false;
  //icons
  import { faPlus } from '@fortawesome/free-solid-svg-icons';
  library.add(faPlus);    

  export default {  
    props:['p-data'],
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    data(){
      return {
        searchGirls:[],
        searchMen:[],
        fromList:[],
        toList:[],
        //search        
        girlSearchSettings:[],
        manSearchSettings:[],
        //pages
        girlsPage:1,
        menPage:1,
        girlsPages:1,        
        menPages:1,        
        //Add ALL
        toAllMen:false,
        toListTotal:false,
        searchMenShow:true,
        searchGirlsShow:true,
      }
    },           
    mounted() {
      let l = this.loading('search-container');
      // this.getRecentGirls();
      // this.getRecentMen();
      this.hideLoading(l);
    },
    methods: {
      async getRecentGirls(){
        let r = await this.ax('get','/admin/mailer/recent/girls');
        if(r) this.searchGirls = r['data'];
      },
      async getRecentMen(){
        let r = await this.ax('get','/admin/mailer/recent/men');
        if(r) this.searchMen = r['data'];
      },
      addFromList(id){
        if(this.fromList.find(x=>x.id == id)) {
          this.showErrors(id+' alredy listed',false,2500);
          return;
        }

        let add = this.searchGirls.find(x=>x.id == id);
        this.fromList.push(add);
      },
      addToList(id){
        if(this.toList.find(x=>x.id == id)) {
          this.showErrors(id+' alredy listed',false,2500);
          return;
        }
        let add = this.searchMen.find(x=>x.id == id);
        this.toList.push(add);
      },
      async addAllMen(){
        let l = this.loading('.men-container');
        let r = await this.ax('get','/admin/mailer/men/count');

        if(!r) {this.hideLoading(l); return;}

        //Remove search
        this.searchMenShow = false;

        //Show total
        this.toListTotal = r;
        //Update to List
        this.toList = [];
        //Set to all
        this.toAllMen = true;

        this.hideLoading(l);
      },
      async removeListMen(){
        this.toList = [];
        this.toListTotal = 0;
        this.searchMenShow=true;
        this.toAllMen=false;
      },
      async removeListGirls(){
        this.fromList = [];
        this.fromListTotal = 0;
        this.searchGirlsShow=true;
      },
      addFromSearchMen(){
        $.each(this.searchMen, (i, v) => {
          this.addToList(v.id);
        });
      },
      addFromSearchGirls(){
        $.each(this.searchGirls, (i, v) => {
          this.addFromList(v.id);
        });
      },
      async createMailer(type){

        let l = this.loading('.mailer-container');

        let r = await this.ax('put','/admin/mailer/send/letters',{
                                                              from:this.fromList,
                                                              to:this.toList,
                                                              toall:this.toAllMen,
                                                              type:type
                                                            })

        if(!r){
          this.hideLoading(l);
          return;
        }

        // location.reload();
      },
      //Search pages
      girlSearch(search){
        this.girlSearchSettings = search;
        this.girlsPage = 1;
        this.getGirl();
      },
      girlChangePage(page){
        this.girlsPage =page;
        this.getGirl();
      },
      async getGirl(){
        let l = this.loading('.girls-search-container');
        let r = await this.ax('get','/admin/girl/search',{page:this.girlsPage,search:this.girlSearchSettings})
        if(!r){
          this.hideLoading(l);
          return false;
        }

        this.searchGirls = JSON.parse(r.data);
        this.girlsPages = JSON.parse(r.settings).pages;

        this.hideLoading(l);
      },
      menSearch(search){
        this.manSearchSettings = search;
        this.menPage = 1;
        this.getMen();
      },
      manChangePage(page){
        this.menPage =page;
        this.getMen();
      },
      async getMen(){
        let l = this.loading('.men-container');
        let r = await this.ax('get','/admin/man/search',{page:this.menPage,search:this.manSearchSettings})
        if(!r){
          this.hideLoading(l);
          return false;
        }

        this.searchMen = JSON.parse(r.data);
        this.menPages = JSON.parse(r.settings).pages;

        this.hideLoading(l);
      },

    } 
  }
</script>
