<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light">
      <!-- brand -->
      <a class="navbar-brand" href="">Charming Brides</a>
      <!-- toggle button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- navlist -->
      <div class="maxw container-fluid">
        <div class="collapse navbar-collapse rounded" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <!-- home -->
            <li class="nav-item">
              <a class="nav-link" href="/">Home</a>
            </li>
            <!-- news -->
            <li class="nav-item">
              <a class="nav-link" href="/news">News</a>
            </li>            
            <!-- about -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              About
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/about">About us</a>
                <a class="dropdown-item" href="/branches">Branches</a>
                <a class="dropdown-item" href="/our/couples">Our couples</a>
                <a class="dropdown-item" href="/ukraine">About Ukraine</a>
                <a class="dropdown-item" href="/holidays">Holidays</a>
              </div>
            </li>
            <!-- girls -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Girls
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/new/girls">New Girls</a>
                <a class="dropdown-item" href="/all/girls">All Girls</a>
              </div>
            </li>
            <!-- services -->
            <li class="nav-item dropdown service-menu">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Services
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/memberships">Memberships</a>
                <a class="dropdown-item" href="/services">All Services</a>
                <!-- Services -->
                <a 
                  v-if="serviceMenu.services != undefined"
                  v-for="m in serviceMenu.services" 
                  :href="'/service/' + m.id"
                  class="dropdown-item" 
                >
                  {{m.name}}                              
                </a>
                <!-- Categories -->
                <a 
                  v-if="serviceMenu.categories != undefined"
                  v-for="m in serviceMenu.categories" 
                  :href="'/service/category/' + m.id"
                  class="dropdown-item" 
                >
                  {{m.name}}                              
                </a>
              </div>
            </li> 
            <!-- Help -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Help            </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">                
                <a class="dropdown-item" href="/faq">FAQ</a>
                <a class="dropdown-item" href="/how/to/start">How to Start</a>
              </div>
            </li>
            <!-- contacts -->
            <li class="nav-item">
              <a class="nav-link" href="/contacts">Contacts</a>
            </li> 

          </ul>               

          <a v-if="auth" class="nav-link" href="/chat">
            <fa-icon icon="comments" /> ONLINE CHAT
          </a>

          <a
            v-if="auth" 
            class="nav-link" 
            href="/letters"
            :class="(noty.l > 0) ? 'notification':''"
          >
            <fa-icon icon="envelope" /> Letters  {{(noty.l > 0) ? '('+noty.l+')':''}}</a>
          </a>

          <a 
            v-if="auth" 
            class="nav-link" 
            href="/matched"
            :class="(noty.s > 0) ? 'notification':''"
          >
            <fa-icon icon="heart" /> Signs of Interest   {{(noty.s > 0) ? '('+noty.s+')':''}}</a>
          </a>

          <a v-if="auth && role < 3" class="nav-link" href="/profile">
            <fa-icon icon="user" /> Profile
          </a>

          <a v-if="auth && role > 2" class="nav-link" href="/admin">
            Admin Panel 
          </a>

          <a v-if="auth" class="nav-link" href="" @click.prevent="logout()">  
             <fa-icon icon="sign-out-alt" /></i> Logout
          </a>

          <a v-if="!auth" class="nav-link" href="/login">     <i class="icon-login"></i> Login </a>
          <a v-if="!auth" class="nav-link" href="/register">  <i class="icon-user-plus"></i> Register</a>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>

  // Font Awsome   
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  // Vue.component('fa-icon', FontAwesomeIcon); 
  import { library } from '@fortawesome/fontawesome-svg-core';    

  import { faSignOutAlt } from '@fortawesome/free-solid-svg-icons';
  library.add(faSignOutAlt);

  import { faUser } from '@fortawesome/free-solid-svg-icons';
  library.add(faUser);

  import { faComments } from '@fortawesome/free-solid-svg-icons';
  library.add(faComments);

  import { faHeart } from '@fortawesome/free-solid-svg-icons';
  library.add(faHeart);

  import { faEnvelope } from '@fortawesome/free-solid-svg-icons';
  library.add(faEnvelope);

  export default {        
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    props:['p-user'],
    data(){
      return {
        noty:{
          l:0,
          s:0,
        },
        serviceMenu:[],
      }
    },
    computed:{
      auth:function(){
        if(this.pUser == ""){
          return false;
        }else{
          return true;
        }
      },
      role:function(){
        if(this.auth){
          return $.parseJSON(this.pUser).role;
        }else{
          return false;
        }   
      }
    },  
    mounted() {
      this.getNotifications();
      this.getServiceMenu();
    },          
    methods: {
      async logout(){
        await this.ax('post','/logout');
        location.reload();
      },
      async getNotifications(){
        let r = await this.ax('get','/notifications');

        if(!r) return;

        this.noty = r;
      },
      async getServiceMenu(){
        let l = this.loading('.service-menu');
        let r = await this.ax('get','/service/get/menu');

        if(!r) {
          this.hideLoading(l);
          return;
        }

        this.serviceMenu = r;
        this.hideLoading(l);
      }      
    }
  }
</script>

<style scoped>
  
  .notification{
    color:yellow !important;
  }


</style>