<template>
    <div class="container-fluid">
        <!-- Example -->

        <div class="admin-girls-list row p-2">
          <div 
            class="admin-girl user-item p-1 m-2" 
            v-for="girl in girls"
            v-bind:class="{'admin-girl-active' : user.id == girl.id}"
            @click="user = girl"
          >
             <!-- Photo -->
            <div class="img-wrapper text-center">
              <img 
                class="" 
                :src="assets+'/media/gallery/'+girl.id+'_0.jpg'" 
                :alt="girl.name"
              >
            </div>
            <!-- Name -->
            <div class="info-wrapper">
              {{girl.name}} 
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
          }
        },
        async mounted() {

          let l = this.showLoading('.admin-girls-list');

          //Get girls
          await this.getUsers();

          //check pre from
          let girl = undefined;
          let queryString = this.getUrlVars();
          if(queryString.from !== undefined){
            girl = this.girls.find(x => x.id == queryString.from);
            if(girl !== undefined){
              this.user = girl;
            }
          }

          this.hideLoading(l);
        },
        methods: {
          async getUsers(){
            // @@@ loaders
            let girls = await this.ax('get', 'letter/get/girls');

            if(girls)
              this.girls = girls;
            
          },
          setGirl(){
            //
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
          }  
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