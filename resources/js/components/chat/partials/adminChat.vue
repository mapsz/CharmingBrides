<template>
    <div class="container-fluid chat-admin-settings">
        <div class="row">
          <div class="col">
            <h1>Admin settings</h1>
          </div>
          <div class="col">
            <admin-online-girls 
              :p-online-users="pOnlineUsers"
             />
          </div>          
        </div>
      <div class="chat-admin-girl-search">
        <h2>Search Girls</h2>
        <div class="col-12">
          <juge-search
            :p-search="pSearch"
            :p-param-route="'/parametrs/girl'"
            @doSearch="searchGirl"
          ></juge-search>            
          <div class="row chat-admin-girl-search-list p-1">
            <div v-for="girl in searchList" class="col mb-2">
              <center>
                <img 
                  v-bind:class="{'active-girl': girl.id == user.id}"
                  :src="girl.photo[0]" 
                  alt="" 
                ><br>              
                ({{girl.id}}) {{girl.name}}<br>
                <span v-if="girl.agent" class="girl-search-agent">{{girl.agent}}</span>
                <span v-else style="color: gray;font-style: italic;">no agent</span>
                <br>
                <button class="btn btn-primary" @click="setHardOnline(girl.id)">Set Online</button>
              </center>
            </div>
          </div>
        </div>
      </div>   
      <!-- Online List -->
      <div>
        <h2>Your Online Girls</h2> 
        <div class="col-12">
          <div class="row">
          	<div
          	v-for="hardOnlineUser in hardOnline"
          	class="col p-1">
              <center>
                <div                       
                  class="hardOnlineUser"                       
                  @click="selectUser(hardOnlineUser.id)"
                >
                  <img 
                    v-bind:class="{'active-girl': user.id == hardOnlineUser.id}"
                    :src="hardOnlineUser.photo[0]" 
                    alt="" 
                  ><br>
                  <span 
                    v-bind:class="{'text-success': user.id == hardOnlineUser.id}">
                      ({{hardOnlineUser.id}}) {{hardOnlineUser.name}}
                  </span><br>  
                  <span v-if="hardOnlineUser.read" class="text-warning">
                      New message!
                      <br>
                  </span>            
                  <button class="btn btn-sm btn-danger" @click="deleteHardOnline(hardOnlineUser.id)">Set Offline</button>
                </div>
              </center>
            </div>      		
        	</div>
        </div>
      </div>  
    </div>
</template>

<script>
  export default {
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    props:['p-online-users','p-invite-in','pSearch'],
  	data(){
      return {
        hardOnline:[],
        user: false,
        search:"",
        searchList:[],
        inviteIn:this.pInviteIn,
      }
    },
    watch: {
      hardOnline: {
        deep:true,
        handler:function($old,$new){
          this.$emit('hard-online',this.hardOnline);
          console.log($old);
          console.log($new);
        },
      }    
    },       
    async mounted(){
    	await this.getHardOnline();

      $.each(this.hardOnline, (index, val) => {
        this.getNewMessages(val.id);
      });
    },
    methods: {
    	async getHardOnline(){
        var r = await axios.get('/chat/getHardOnline' )
          .then((r) => {
              if(!r.data) return false;

              if(r.data.error){
                  return false;
              }

              return r.data;
          })
          .catch((r) => {return false;});

        return this.hardOnline = r;
    	},
    	resetHardOnline(){
    		//
    		this.$emit('onlineReset');
    	},
    	async setHardOnline(setHardOnlineId){
        var r = await axios.post('/chat/admin/setOnline',{id:setHardOnlineId})
            .then((r) => {
                if(!r.data) return false;

                if(r.data.error){
                    return false;
                }

                return r.data;
            })
            .catch((r) => {return false;});


        if(r){           		
          this.getHardOnline();
          this.resetHardOnline();
          return true;
        }else{
        	return false;
        }
    	},
    	async deleteHardOnline(userId){

         var r = await axios.delete('/chat/admin/deleteOnline',{data: {id:userId}})
              .then((r) => {
                  if(!r.data) return false;

                  if(r.data.error){
                      return false;
                  }

                  return r.data;
              })
              .catch((r) => {return false;});

          if(r){
              this.getHardOnline();
              this.resetHardOnline();
              this.user = false;
              this.$emit('selectUser', this.user);
              return true;
          }else{
          	return false;
          }
    	},
    	selectUser(id){
    		let user = this.hardOnline.find(x => x.id === id);
    		this.user = user;
    		this.$emit('selectUser', user);
    	},
      // async searchGirl(button = false){
      //   if(!button)
      //     if(this.search.length < 3) return false;
        
      //   let l = this.showLoading('.chat-admin-girl-search-list');

      //   // let r = await this.ax('get','/chat/search/girl',{'search':this.search});
      //   let r = await this.ax('get','/all/girl/search',{search:{search:this.search}})

      //   this.searchList  = JSON.parse(r.data);

      //   this.hideLoading(l);
      // },
      async searchGirl(search){
        let l = this.loading('.chat-admin-girl-search-list');
        let r = await this.ax('get','/admin/girl/search',{page:1,search:search,chat:1})
        if(!r){
          this.hideLoading(l);
          return false;
        }

        this.searchList = JSON.parse(r.data);

        this.hideLoading(l);
      },      
      async getNewMessages(id){
          var r = await this.ax('get','/chat/recentRooms',{user_id:id});

          if(!r) return false;

          $.each(r, (index, val) => {
            if(val.read != undefined){
              if(val.read == false){
                let k = this.hardOnline.findIndex(x => x.id == id);
                if(k >= 0){
                  this.hardOnline[k].read = true;
                }
                let g = this.hardOnline;
                this.hardOnline = [];
                this.hardOnline = g;                    
                return;
              }
            }
          });
          return;
      }
    }
  }
</script>


<style scoped>

  .chat-admin-girl-search img, .hardOnlineUser img{
    height:100px;
  }

	.hardOnlineUser:hover{
		text-decoration: underline;
		color:green;
	}

	.hardOnlineUser{
		cursor: pointer;
	}

  .active-girl{
    border:solid limegreen 3px;
  }

  .chat-admin-settings{
    border: 1px solid red;
    border-radius: 7px;
    padding-bottom: 10px;
    margin-bottom: 10px;
    padding-left: 10px;
    padding-top: 10px;
    background-color: #ffbababa;
  }
  .girl-search-agent {
      color: #3f00ff;
  }
</style>