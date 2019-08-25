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
        <letter-component :p-user="user"/>
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            assets:assets,
            user:false,
            girls:[],
          }
        },
        mounted() {
            console.log('Component mounted.')
            this.getUsers();
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