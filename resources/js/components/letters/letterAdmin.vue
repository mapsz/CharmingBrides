<template>
    <div class="container-fluid">
      <div class="container py-3">
        <div class="row">
          <input type="text" @keyup="searchGirl()" v-model="search">
          <button class="btn btn-primary" @click="searchGirl(true)">Search</button>
        </div>   
        <div class="admin-girls-list row p-2">
          <div 
            class="admin-girl user-item p-1 m-2" 
            v-for="girl in girls"
            v-bind:class="{'admin-girl-active' : user.id == girl.id}"
            @click="setGirl(girl)"
          >
             <!-- Photo -->
            <div class="img-wrapper text-center">
              <img 
                class="" 
                :src="'/'+girl.photo[0]" 
                :alt="girl.name"
              >
            </div>
            <!-- Name -->
            <div class="info-wrapper">
              {{girl.name}}<br>
              {{girl.id}} 
            </div>
          </div>
        </div>
      </div>
      <letter-component :p-user="pUser" :p-user-from="user" />
    </div>
</template>

<script>
    export default {    
        props:['p-user'],    
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            assets:assets,
            user:false,            
            girls:[],
            search:"",
          }
        },
        async mounted() {

          let l = this.showLoading('.admin-girls-list');

          //Get girls
          // await this.getGirls();

          //check pre girl
          let girl = undefined;
          let queryString = this.getUrlVars();
          if(queryString.girl !== undefined){
            //Get girl
            await this.getGirl(queryString.girl);
            girl = this.girls.find(x => x.id == queryString.girl);
            if(girl !== undefined){
              this.user = girl;
            }
          }

          this.hideLoading(l);
        },
        methods: {
          async getGirls(){
            let l = this.showLoading('.admin-girls-list');
            let girls = await this.ax('get', 'admin/letter/girls');

            if(girls)
              this.girls = girls;

            this.hideLoading(l);
            
          },
          async getGirl(id){
            let l = this.showLoading('.admin-girls-list');
            let columns = [
              {'name' : 'name'},
              {
                'name' : 'id',
                'relation' : 'user.id'
              },
              {
                'name'        : 'birth',
                'timeFormat'  : 'Y M j'
              },           
              {          
                'name' : 'agent',
                'caption' : 'agent',
                'relationBelongsToOne' : 'agent.name',
              },  
              {
                'name'    : 'photo',
                'file'    : 'image',
              },
            ];

            let r = await this.ax('get','/girl/get/'+id,{'columns':columns});

            this.girls = [r];


            this.hideLoading(l);
            
          },
          setGirl(girl){
            this.user = girl;
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
          } ,
          async searchGirl(button = false){
            if(!button)
              if(this.search.length < 3) return false;
            
            // let l = this.showLoading('.chat-admin-girl-search-list');

            let columns = JSON.stringify([
              {'name' : 'name'},
              {
                'name' : 'id',
                'relation' : 'user.id'
              },
              {
                'name'        : 'birth',
                'timeFormat'  : 'Y M j'
              },           
              {          
                'name' : 'agent',
                'caption' : 'agent',
                'relationBelongsToOne' : 'agent.name',
              },  
              {
                'name'    : 'photo',
                'file'    : 'image',
              },
            ]);

            let r = await this.ax('get','chat/search/girl',{'search':this.search});

            this.girls = r.data;

            // this.hideLoading(l);
          },
        }
    }
</script>

<style scoped>

  .admin-girl .img-wrapper{
    height:100px;
  }
  
  .admin-girl img{
    height:100%;
  }

  .admin-girl{
    cursor:pointer;
    border: 1px solid #0339fe;
  }
  .admin-girl:hover{
    background-color: #0008ff61;
  }

  .admin-girl-active{
    background-color: #0008ff61;
  }
</style>