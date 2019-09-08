<template>
  <div class="container-fluid h-100 py-2">

      <loading :loading="loading.screen"/>

      <div v-if="reconnect && !onlineConnection.subscribed" class="reconnect">
        <div class="reconnect-container">
          <span>Connected:{{onlineConnection.subscribed}}</span><br>
          <span>Pending:{{onlineConnection.pending}}</span><br>
          <span>Canceled:{{onlineConnection.cancelled}}</span>

          <p class="text-danger"><b>Connecting...</b></p>
          <center>
            <button class="btn btn-primary" @click="ReconnectOnline()">Reconnect</button>
          </center>
        </div>
      </div>

      <admin-chat 
          v-if="prop_user.man >= 3" 
          :p-online-users="onlineUsers"
          @onlineReset="onlineReset()"
          @selectUser="selectUser"
      />              
      <div class="row justify-content-center h-100">
          <!-- Online -->
          <online-chat
            :p-user="user"
            :p-online-users="onlineUsers"
            :p-companion="room.companion"
            :p-connection="onlineConnection"
            @selectRoom="selectRoom"
          ></online-chat>
          <!-- Chat -->
          <div class="col-md-6 col-xl-6 chat">
              <div class="center-block card">
                  <div v-show="loading.room" class="loading">
                    <span class="p-2" style="color:white">Loading...</span>
                  </div>  
                  <!-- Header -->
                  <div class="card-header msg_head">
                    <div v-if="room" class="d-flex bd-highlight">
                      <!-- Avatar -->
                      <div class="img_cont">
                        <img 
                          :src="assets+'/media/gallery/'+companion.id+'_0.jpg'"
                          class="rounded-circle user_img"
                        >
                        <span v-bind:class="{'offline': !onlineUsers.some(e => e.id == companion.id)}" class="online_icon"></span>
                      </div>
                      <!-- Info -->
                      <div class="user_info">
                        <span>
                          {{room.companion.name}} {{room.companion.surname}}
                        </span>
                        <p>{{room.id}} - room</p>
                      </div>
                      <!-- Connection info -->
                      <div class="user_info">
                        <fa-icon icon="link" 
                          :class="{
                            'text-success'      : privateConnection.subscribed,
                            'text-warning'      : privateConnection.pending,
                            'text-danger'       : privateConnection.canceled
                          }"  
                        />
                      </div>                                  
                      <!-- Balance -->
                      <div v-if="user.man" class="user_info">
                        <span>
                            Balance: {{user.balance}}$
                        </span>
                        <p>1  minute - {{user.membership.chat_price}}$  {{user.membership.name}}</p>
                      </div>
                      <!-- Timer -->
                      <div v-if="user.man" class="user_info">
                        <span>
                           {{payedChatCaptions.currentTimer}}
                        </span>
                        <p>Total: - {{payedChatCaptions.totalPrice}}$</p>
                      </div>                                      
                      <!-- Stop     -->
                      <div v-if="user.man" class="user_info">
                        <button class="btn btn-primary" @click="stopPayedChat(room.id)">Stop</button>
                      </div>                                 
                      <!-- @@@ Timer pause -->
                    </div>
                      <!-- More @@@ ?? -->
                      <!-- <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
                      <div class="action_menu">
                          <ul>
                              <li><i class="fas fa-user-circle"></i> View profile</li>
                              <li><i class="fas fa-users"></i> Add to close friends</li>
                              <li><i class="fas fa-plus"></i> Add to group</li>
                              <li><i class="fas fa-ban"></i> Block</li>
                          </ul>
                      </div> -->
                  </div>

                      
                  <!-- Body -->
                  <div class="card-body msg_card_body" v-chat-scroll>
                    <div v-for="message in messages">
                      <!-- Messages -->
                      <div 
                        v-bind:class="{
                          'justify-content-start': message.user_id == user.id,
                          'justify-content-end' : message.user_id != user.id 
                        }"
                        class="d-flex mb-4"
                      >
                      <img 
                        v-if="message.user_id == user.id"
                        :src="assets+'/media/gallery/'+message.user_id+'_0.jpg'"
                        class="rounded-circle user_img_msg"
                      >
                      <div 
                        v-bind:class="{
                          'man'  : 
                            (message.user_id == user.id && user.man) ||
                            (message.user_id == companion.id && room.companion.man),
                          'msg_cotainer'      : message.user_id == user.id,
                          'msg_cotainer_send' : message.user_id != user.id
                        }"
                      >
                        {{message.body}}
                        <!--<span 
                              v-bind:class="{'msg_time'      : message.user_id == user.id,
                                             'msg_time_send' : message.user_id != user.id}"
                              >                                       
                              {{message.created_at}}
                          </span> -->
                      </div>
                      <img 
                        v-if="message.user_id != user.id"
                        :src="assets+'/media/gallery/'+message.user_id+'_0.jpg'"
                        class="rounded-circle user_img_msg"
                      >
                      </div>
                    </div>
                  </div>                        
                  <!-- Footer -->
                  <div class="send-message card-footer">
                    <!-- Freeze -->
                    <div>
                      <div 
                        v-if="inviteStatus(companion.id) === 1 && user.man === 1 && roomFreeze" 
                        class="freeze"
                      >
                        <center>
                          <button class="btn btn-primary m-4" @click="startPayedChat(room.id)">Start Chat</button>
                        </center>
                      </div>
                      <!-- Invite -->
                      <div class="invite freeze text-white">
                        <div v-if="companion.name != ''">
                          <!-- Deny -->
                          <div v-if="inviteStatus(companion.id) === -1">
                            <center>
                              <p class="m-4">{{companion.name}} Denied</p>
                            </center>
                          </div>
                          <!-- No invite -->
                          <div v-if="inviteStatus(companion.id) === 0 && user.man === 1">
                            <center>
                              <button 
                                class="btn btn-primary m-4" 
                                @click="sendInvite(companion.id,2)"
                              >
                                Invite {{companion.name}} to Chat
                              </button>
                            </center>
                          </div>
                          <!-- Invited -->
                          <div v-if="inviteStatus(companion.id) === 2 && user.man === 1">
                            <center>
                              <p>{{companion.name}} Invited</p>
                              <p>Waiting Answer</p>
                            </center>
                          </div>
                          <!-- Input -->
                          <div v-if="inviteStatus(companion.id) === 3">
                            <center>
                              <button 
                                class="btn btn-primary m-4" 
                                @click="sendInvite(companion.id,1)"
                              >
                                Accept
                              </button>
                              <button 
                                class="btn btn-primary m-4"
                                @click="sendInvite(companion.id,-1)"
                              >
                                Deny
                              </button>
                            </center>
                          </div>                                
                        </div>  
                      </div>
                    </div>                          
                    <!-- Chat send message -->
                    <div class="input-group">
                      <!-- Attach -->
                      <div class="input-group-append">
                        <span class="input-group-text attach_btn">
                          <fa-icon icon="paperclip" />
                        </span>
                      </div>
                      <!-- Send message -->
                      <!-- Text body -->
                      <textarea 
                        v-on:keyup.enter="sendMessage()" 
                        name="text" 
                        class="form-control type_msg" 
                        placeholder="Type your message..."
                      ></textarea>
                      <div @click="emojiPickerShow = !emojiPickerShow" class="input-group-append">
                        <div class="input-group-text emoji_picker">
                          <fa-icon icon="smile"/>
                        </div>
                      </div>
                      <!-- Send button -->
                      <div class="input-group-append" @click="sendMessage()">
                        <span class="input-group-text send_btn">
                          Send <fa-icon icon="arrow-right" class="pl-1"/>
                        </span>
                      </div>
                    </div>                      
                  </div>
              </div>
          </div>
          <div class="col-md-3 col-xl-3 chat">
            <div class="card mb-sm-3 mb-md-0 contacts_card">
              <!-- Recent chats -->
              <recent-chat
                :p-recent-rooms="recentRooms"
                :p-room="room"
                :p-online-users="onlineUsers"
                :p-payed-chat="payedChat"
                @select-room="selectRoom"
              />
              <picker 
                v-show="emojiPickerShow"
                native
                @select="addEmoji" 
                title="Pick your emoji…" 
                emoji="point_up" 
                :style="{ position: 'absolute', bottom: '20px', right: '20px' }" 
              ></picker>
              <div class="card-footer"></div>
            </div>
          </div>
      </div>
  </div>
</template>

<script>

    import VueChatScroll from 'vue-chat-scroll';
    // Font Awsome   
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    // Vue.component('fa-icon', FontAwesomeIcon); 
    import { library } from '@fortawesome/fontawesome-svg-core';    
    Vue.config.productionTip = false;
    //icons
    import { faArrowRight } from '@fortawesome/free-solid-svg-icons';
    library.add(faArrowRight);
    import { faPaperclip } from '@fortawesome/free-solid-svg-icons';
    library.add(faPaperclip);
    import { faLink } from '@fortawesome/free-solid-svg-icons';
    library.add(faLink);
    import { faSmile } from '@fortawesome/free-solid-svg-icons';
    library.add(faSmile);

    //Emoji picker
    import { Picker } from 'emoji-mart-vue';
    Vue.component('picker', Picker);

    export default {
        components: {
            VueChatScroll, 
            d3,
            FontAwesomeIcon,
        },
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['prop_user'],
        data(){
            return {
                loading: {
                  screen: true,
                  room: false,
                },
                user: {},
                room: false,
                connected: false,
                freeze:false,
                messages:[],
                onlineUsers: [],
                recentRooms: [],
                reconnect:false,
                chatOnlineConnection:{},
                chatPrivateConnection:{},
                emojiPickerShow:false,
                //Timer
                timer: {
                  d3: d3.interval((elapsed) => {/**/},99999),
                  time:'0:00',
                  total:'0:00'
                },
                payed:false,
                startBalance:0,
                //private
                payedChat:[],
                history:false,
                inviteOut:[],
                inviteIn:[],
                //files
                assets:assets,   
                //websockets
                pusher : _pusher,
                echo : new _echo({
                  broadcaster: 'pusher',
                  key: process.env.MIX_PUSHER_APP_KEY,
                  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                  // encrypted: true,
                  wsHost: window.location.hostname,
                  wsPort: 6001,
                  disableStats: true,
                }), 
            }
        },
        computed:{
          companion:function(){
            if(this.room.companion != undefined){
              return this.room.companion;
            }
            return {id:false,name:""};
          },
          companionAgree:function(){
            if(this.companion.agree != undefined && this.companion.agree == true){
              return true;
            }

            return false;
          },
          session:function(){
              if(this.chatPrivateConnection != undefined && 
                this.chatPrivateConnection.pusher != undefined && 
                this.chatPrivateConnection.pusher.sessionID != undefined
              ){
                return this.chatPrivateConnection.pusher.sessionID;
              }else{
                return false;
              }
          },
          privateConnection:function(){
            let r = {
              subscribed: false,
              pending:false,
              cancelled:false,
            }

            if(this.chatPrivateConnection.subscription == undefined){
              return r;
            }

            if(this.chatPrivateConnection.subscription.subscribed != undefined){
              r.subscribed = this.chatPrivateConnection.subscription.subscribed;
            }
            if(this.chatPrivateConnection.subscription.subscriptionPending != undefined){
              r.pending = this.chatPrivateConnection.subscription.subscriptionPending;
            }
            if(this.chatPrivateConnection.subscription.subscriptionCancelled != undefined){
              r.cancelled = this.chatPrivateConnection.subscription.subscriptionCancelled;
            }

            return r;
          },
          onlineConnection:function(){
            let r = {
              subscribed: false,
              pending:false,
              cancelled:false,
            }

            if(this.chatOnlineConnection.subscription == undefined){
              return r;
            }

            if(this.chatOnlineConnection.subscription.subscribed != undefined){
              r.subscribed = this.chatOnlineConnection.subscription.subscribed;
            }
            if(this.chatOnlineConnection.subscription.subscriptionPending != undefined){
              r.pending = this.chatOnlineConnection.subscription.subscriptionPending;
            }
            if(this.chatOnlineConnection.subscription.subscriptionCancelled != undefined){
              r.cancelled = this.chatOnlineConnection.subscription.subscriptionCancelled;
            }

            return r;
          },
          payedChatCaptions(){
            let payedChat = this.payedChat.find(x => x.room == this.room.id);
            let r = {
              currentTimer  :'0:00',
              totalPrice    :'0:00'
            }
            if(payedChat != undefined) {
              r.currentTimer = payedChat.captionTime;
              r.totalPrice = payedChat.totalPrice;
            }
              
            return r;
          },
          roomFreeze(){
            let payedChat = this.payedChat.find(x => x.room == this.room.id);
            if(payedChat != undefined) {
              return payedChat.freeze;
            }

            return true;
          }
        },        
        watch: {
            chatOnlineConnection: {
              handler: function (val, oldVal) {this.setChatOnline();},
              deep: true
            }, 
            room:{
              handler: function (val, oldVal) {this.roomHandler(val, oldVal);},
              deep: true               
            },
            freeze:function(val) {
              if(!val || !this.room || !this.user.man) {
                  $('.freeze').hide();
                  return;
              }
              $('.freeze').show();  
              // //Freeze size chat+ footer
              // let headHeight = $('.msg_head').outerHeight();
              // let bodyHeight = $('.chat').outerHeight();
              // let newHeight = bodyHeight - headHeight;
              // $('.freeze').outerHeight(newHeight+'px');
              // $('.freeze').css('margin-top',headHeight);
            }     
        },  
        notifications: {
          showSuccessMsg: {
            type: VueNotifications.types.success,
            title: 'Hello there',
            message: 'That\'s the success!'
          },
          showInfoMsg: {
            type: VueNotifications.types.info,
            title: 'Hey you',
            message: 'Here is some info for you'
          },
          showWarnMsg: {
            type: VueNotifications.types.warn,
            title: 'Wow, man',
            message: 'That\'s the kind of warning'
          },
          showErrorMsg:{
            type: VueNotifications.types.error,
            title: 'Error!',
            message: ""
          }
        },      
        mounted() {
          //set user
          this.user = this.prop_user;

          // Recent Chats
          this.setRecentRoom();

          //Join online broadcast
          this.joinOnline();

          //Get Balance / Membership
          this.getUserMembership(this.user.id);

          //Resend invites
          this.reSendInvites();
        },
        methods: {
          //Emoji
          addEmoji(emoji){
              $('.type_msg').val($('.type_msg').val() + emoji.native); //@@@ between text
              //@@@ close on blank click
          },
          //Online
          async setChatOnline(){
              //Get connection
              let c = this.chatOnlineConnection;
              //Refresh
              let chatOnline = false;
              
              if( c.hasOwnProperty('subscription') && 
                  c.subscription.hasOwnProperty('subscribed') &&
                  c.subscription.subscribed)
              {   //Success
                  chatOnline = true;
                  this.stopLoading();
              }else{
                  //Freeze chat
                  this.startLoading();
                  //Refresh users
                  this.onlineUsers = [];
              }  

              //Exit if success
              if(chatOnline) return true;

              //Show reconnect            
              setTimeout(()=>{
                  //Show reconnect
                  let c = this.chatOnlineConnection;
                  if(!c.hasOwnProperty('subscription'))               { this.reconnect = true; return; }    
                  if(!c.subscription.hasOwnProperty('subscribed'))    { this.reconnect = true; return; }
                  if(!c.subscription.subscribed)                      { this.reconnect = true; return; }
              
              },5000)
          },
          async joinOnline(){
              //leave old chat
              this.leaveOnline();

              //Echo connect                
              let c = await this.echo.join(`chat`)
                  .listen('Chat', ({data}) => {
                    this.eventHandler(data);
                  })                
                  .here((users) => {
                      this.handleOnlineUsers(users);                                 
                  })
                  .joining((user) => {
                      this.handleOnlineUsers(user);
                  })
                  .leaving((user) => {
                      this.handleOnlineUsers(user,true);
                  });

              //Save connection params
              this.chatOnlineConnection = c;

              return true;
          },
          ReconnectOnline(){
              //@@@
              window.location.reload()
          },
          async leaveOnline(){
              this.onlineUsers =[];
              await this.echo.leave('chat');
              this.onlineChat = false;
              return true;
          },
          async onlineReset(){
              await this.leaveOnline();
              await this.joinOnline();
              return true;        
          }, 
          async setRecentRoom(){
              //Get rooms
              let rooms = await this.getRecentRoom();
              //Set rooms
              this.recentRooms = rooms;
          },
          async getRecentRoom(){
              var r = await this.ax('get','/chat/recentRooms',{user_id:this.user.id});

              return r;                
          },
          isOnline(id){
              if (this.onlineUsers.some(e => e.id == id))
                  return true;

              return false;
          },
          photoPath(id){
              //Get user
              let _user = false;

              do{
                  //Check self
                  _user = (this.user.id == id?this.user:false);
                  if(_user){
                      break;
                  } 

                  //Check in online
                  _user = this.onlineUsers.find(x => x.id == id)
                  if(_user){
                      break;
                  } 
                  
                  //Check in recent chats
                  _user = this.recentRooms.find(x => x.companion.id == id);
                  if(_user){
                      break;
                  }

              }while(false);

              
              if(_user){
                  let path = 'media/';
                  _user.man ? path+='mans' : path+='girls';
                  path+='/gallery/'+_user.id+'_0.jpg';
                  return path;
              }else{
                  let error = 'error getting user '+id+' path'
                  this.showErrorMsg({message:error})
                  return false;
              }
          },
          //Online
          addOnlinetUser(user){
            this.onlineUsers.push(user);
          },
          removeOnlineUser(user){
            this.onlineUsers.splice(this.onlineUsers.indexOf(user), 1);
          },
          handleOnlineUsers(users,del = false){

            //Form data

              // set single user ass array
              if(!Array.isArray(users)){
                users = [users];
              }

              // get array from array
              $.each(users, (i, v) => {
                if(Array.isArray(v)){
                  $.each(v, (j, k) => {
                     users.push(k);
                  });
                  delete users[i];
                }
              });

            //Hadnle data

              $.each(users, (i, v) => {
                if(typeof(v) === "object"){
                  if(!del){
                    //Add
                    this.addOnlinetUser(v);
                  }else{
                    //del
                    this.removeOnlineUser(v);                    
                  }

                }
              });

              return;

              // Check if array
              if(Array.isArray(users)){
                  // Add array
                  users.forEach((user) => {
                      // Check if array again
                      if(Array.isArray(user)){
                          user.forEach((u) => {
                              if(typeof(u) === "object"){
                                  if(del){
                                      this.onlineUsers.splice(this.onlineUsers.indexOf(u), 1);
                                  }else{
                                    if(u.man != this.user.man)
                                      this.onlineUsers.push(u);
                                  }
                              }
                          })
                      }else{
                          if(typeof(user) === "object"){
                              if(del){
                                  this.onlineUsers.splice(this.onlineUsers.indexOf(user), 1);
                              }else{
                                if(user.man != this.user.man)                   
                                  this.onlineUsers.push(user);
                              }
                          }
                      }
                  });
              }else{
                // Add single
                if(typeof(users) === "object"){
                  console.log(users);
                  if(del){
                    this.onlineUsers.splice(this.onlineUsers.indexOf(users), 1);
                  }else{
                    if(users.man != this.user.man)                   
                      this.onlineUsers.push(users);
                  }
                }
              }
          },
          selectUser(user){
              this.loading.room = true;
              this.user = user;
              this.disconnectRoom();
              this.loading.room = false;
          },
          async getUserMembership(userId){

              //Set membership
              this.user.membership = {};

              if(this.user.man != 1) return false;

              var r = await this.ax('get','/memberships/current',{user_id: userId});

              if(r){
                  this.user.membership = r;
              }else{
                  this.user.membership.name = 'none';
              }

              return true;
          },
          // Private Room
          inviteStatus(id){
            //Invites
            let out = this.inviteOut.find(x => x.id == id);
            let $in =  this.inviteIn.find(x => x.id == id);

            //Deny
            if($in != undefined && $in.status == -1)return -1;

            //No invite
            if(!out && !$in)return 0;

            //Accept
            if(out && $in)return 1;

            //Invited
            if(out && !$in) return 2;

            //Input
            if(!out && $in) return 3;

            return invite.status;
          },
          async selectRoom(companionId){

            //check active chat
            if(this.user.man === 1){
              let i = this.payedChat.findIndex(x => x.room == this.room.id);
              if(i > -1){
                console.log('active chat');
                //continue

                //cancel
                // return;


                //stop
                // this.stopPayedChat(this.room.id);


              }
            }

            this.loading.room = true;
            await this.disconnectRoom();

            //Get Room
            var room = await this.getRoom(companionId);
            if(!room){
                let error = 'error getting room';
                this.showErrorMsg({message:error});
                this.loading.room = false;
                return false; //@@@ add some error
            } 

            //Set room
            room.connect = true;
            this.room = room;

            //Set readed
            this.readRoom();
            //freeze room
            this.freezeRoom();
          },
          async roomHandler(val, oldVal){
              //Exit room
              if(!val) return;                

              //Set room loading
              this.loading.room = true;

              //Set messages
              await this.getMessages();

              //Connect
              if(val.connect){
                //Connect room
                await this.connectRoom();
                this.loading.room = false;
                return;
              }else{
                //Disconnect room  
                await this.disconnectRoom();             
                this.loading.room = false;
                return;
              }
          },            
          async connectRoom(){

            //Connect chanel
            let c = await this.echo.join('privateChat.' + this.room.id) //@@@ private chat
              .listen('PrivateChat', ({message}) => {
                  this.messages.push(message);
              })

            //Save connection params
            this.chatPrivateConnection = c;  

            return;                        
          },
          async disconnectRoom(){
              //Exit if no room
              if(!this.room) return;
              //Disconncet room
              let l = await this.echo.leave('privateChat.' + this.room.id);           
              //Remove room
              this.room = false;
              this.chatPrivateConnection = {};

              return;
          }, 
          async getRoom(companionId){
             var r = await axios.get('/room', {
                      params: {
                          'companionId':companionId,
                          'userId':this.user.id
                      }
                  })
                  .then((r) => {
                      if(!r.data) return false;

                      if(r.data.error == 1){
                          this.showErrorMsg({message:r.data.text});
                          return false;
                      }

                      return r.data;
                  })
                  .catch((r) => {return false;});
              return r;
          },
          async readRoom(){
            //Set front read
            this.recentRooms[this.recentRooms.findIndex(x => x.id == this.room.id)].read = true;

            //Set back read
            this.ax('post','/chat/read',{'user_id':this.user.id,'room_id':this.room.id});
          } ,
          //Payed chat
          async startPayedChat(roomId){

            let l = this.showLoading('.send-message.card-footer');
            //check exists
            let i = this.payedChat.findIndex(x => x.room == roomId);
            if(i > -1){this.hideLoading(l); return;}

            let payedChat = {};
            payedChat.room = roomId;

            //Check balance
            if(this.user.man === 1){
              if(parseFloat(this.user.membership.chat_price) > parseFloat(this.user.balance)){         
                this.showErrors("Low Balance!");
                return;
              }
            }

            // Start History
            let h = await this.ax('put','chat/history',{room:roomId,session:this.session});
            if(!h){this.hideLoading(l);return false;}
            payedChat.history = h;

            //Unfreeze Room
            // this.unFreezeRoom();
            payedChat.freeze = false;

            //set pays
            payedChat.startBalance = this.user.balance;
            payedChat.pricePerSecond = this.user.membership.chat_price / 60;
            payedChat.totalPrice = parseFloat(this.user.membership.chat_price).toFixed(2);
            payedChat.totalPayed = parseFloat(0).toFixed(2);
            payedChat.captionTime = '0:00';
            payedChat.payed = false;

            //edit balance
            this.user.balance = (this.user.balance-this.user.membership.chat_price).toFixed(2);

            //Add timer
            payedChat.timer = this.startRoomTimer(roomId);

            console.log(payedChat);

            this.payedChat.push(payedChat);

            this.hideLoading(l);
          },
          stopPayedChat(roomId){

            let i = this.payedChat.findIndex(x => x.room == roomId);

            //stop time
            console.log(this.payedChat[i].timer);
            this.payedChat[i].timer.stop();            
            //Stop history
            this.ax('post','chat/stop',{'history':this.payedChat[i].history});

            //delete payed chat
            // delete this.payedChat[i];
            this.payedChat[i].room = -1;
          },
          startRoomTimer(roomId){

            return d3.interval((elapsed) => 
            {

              let i = this.payedChat.findIndex(x => x.room == roomId);

              // console.log(this.payedChat[i]);
              // //Timer                    
              let seconds = parseInt(elapsed / 1000);
              let minutes = parseInt(seconds / 60); 
              let _seconds = seconds % 60;
              this.payedChat[i].captionTime = minutes+":"+_seconds;


              if(seconds > 60){
                //Total
                this.payedChat[i].totalPrice = (
                  parseFloat(this.payedChat[i].pricePerSecond * seconds)                   
                  // parseFloat(this.user.membership.chat_price)
                ).toFixed(2);
                //Pay
                if(seconds % 10 == 0 && seconds > 61){
                  if(!this.payedChat[i].payed){
                    this.payChat(this.payedChat[i].history);
                    this.payedChat[i].payed = true;
                  }
                }else{
                  this.payedChat[i].payed = false;
                }

                //Balance
                let pay = parseFloat(this.payedChat[i].totalPrice) -
                          parseFloat(this.payedChat[i].totalPayed);
                            

                          
                pay = (pay).toFixed(2);

                console.log(pay);

                if(pay < 0) pay = 0;              

                

                this.user.balance = (this.user.balance - pay).toFixed(2);

                this.payedChat[i].totalPayed = parseFloat(this.payedChat[i].totalPayed) + 
                                               parseFloat(pay);

                //Low balance
                if(this.user.balance < 0){
                  this.user.balance = (0).toFixed(2);
                  this.stopPayedChat(roomId);
                  this.showErrors("Low Balance!");
                }
              }
                
            }, 250);
          },
          stopRoomTimer(){
              this.timer.d3.stop();
              this.timer.time = "0:00";
              this.timer.total = "0:00";
          },
          payChat(history){
            console.log('pay');
            let r = this.ax('post','/chat/pay',{'history':history});
          },
          async sendInvite(companion,status,silence= false){
            
            let companionId = companion;
            let l;
            if(!silence){
              l = this.showLoading('.freeze.invite');
            }

            let r = await this.ax('post','/chat/invite',{'from':this.user.id,"to":companionId,'status':status});
            if(!r){
              if(!silence)this.hideLoading(l);
              return;
            }

            if(!silence){this.inviteOut.push({'id':companionId,'status':status});}

            if(!silence)this.hideLoading(l);
            return;
          },
          reSendInvites() {
            setTimeout(() => {
              if(this.inviteOut.length > 0){
                $.each(this.inviteOut, (index, v) => {
                  if(this.inviteStatus(v.id) === 2){
                    this.sendInvite(v.id,2,true);
                  };
                });

              }
              this.reSendInvites();
            }, 15000);
          },
          freezeRoom(){
              if(this.user.man)
                  this.freeze = true;
              else
                  this.freeze = false;
          },
          unFreezeRoom(){
              //
              this.freeze = false;
          },
          //Messages
          async getMessages(){
              var r = await axios.get('/chat/messages', {
                      params: {
                          'userId':this.user.id,
                          'roomId':this.room.id,
                      }
                  })
                  .then((r) => {
                      if(!r.data) return false;

                      if(r.data.error){
                          this.showErrorMsg({message:r.data.text});
                          return false;
                      }

                      if(!r.data.messages){
                      }

                      this.messages = r.data.messages;

                  })
                  .catch((r) => {return false;});             
          },
          async sendMessage(){
            //Clear message
            let message = $('.type_msg').val();
            $('.type_msg').val("");
            let session = this.chatPrivateConnection.pusher.sessionID;
            
            let data = {
                    'userId':this.user.id,
                    'roomId':this.room.id,
                    'history':this.history,
                    'session': session,
                    'body': message
                }

            let r = await this.ax('put','/chat/messages',data);
          },
          //Notifications
          eventHandler(data){
            // console.log(data);

            switch (data.type) {
              case 'message':
                // check if current room
                if(this.room.id == data.room) return;
                //Check if user room
                let i = this.recentRooms.findIndex(x => x.id == data.room);
                if(i < 0) return;
                //Set unread
                this.recentRooms[i].read = false;
                break;
              case 'invite':
                if(data.to == this.user.id){
                  if(this.inviteStatus(data.from) === 1) {
                    this.sendInvite(data.from,1)
                  }

                  this.inviteIn.push({"id":data.from,"status":data.status});
                }
                break;
              default:
                return;
                break;
            }
          },
          //Other
          startLoading(){
            this.loading.screen = true;
          },
          stopLoading(){
            this.loading.screen = false;
          }
        }
    }

</script>

<style>
    .reconnect{
        position: absolute;
        width: 100%;
        height: 100%;
        z-index:300;
    }
    .reconnect-container{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        border: 1px black solid;
        border-radius: 7px; 
        padding:10px;
        background-color: #fdffe0;
        font-size: 22pt;       
    }
    .chatContainer{
        height: 100%;
        margin: 0;
        background: #7F7FD5;
        background: -webkit-linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
        background: linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
    }
    .loading {
        height: 100%;
        width: 100%;
        background-color: #000000bf;
        position: absolute;
        z-index: 300;
        border: 2px solid black;
        border-radius: 10px;        
    }
    .freeze {
        z-index: 200;
        width: 100%;
        position: absolute;
        background-color: #00082eeb !important;
        border: 1px solid black !important;
        border-radius: 0px 0px 10px 10px !important;    
        bottom: -1px;
        left: 0px;    
    }    
    .chat{
        margin-top: auto;
        margin-bottom: auto;
    }
    .card{
        height: 500px;
        border-radius: 15px !important;
        background-color: rgba(0,0,0,0.4) !important;
    }
    .contacts_body{
        padding:  0.75rem 0 !important;
        overflow-y: auto;
        white-space: nowrap;
    }
    .msg_card_body{
        overflow-y: auto;
    }
    .card-header{
        border-radius: 15px 15px 0 0 !important;
        border-bottom: 0 !important;
    }
     .card-footer{
        border-radius: 0 0 15px 15px !important;
        border-top: 0 !important;
    }
    .container{
        align-content: center;
    }
    .search{
        border-radius: 15px 0 0 15px !important;
        background-color: rgba(0,0,0,0.3) !important;
        border:0 !important;
        color:white !important;
    }
    .search:focus{
         box-shadow:none !important;
       outline:0px !important;
    }
    .type_msg{
        background-color: rgba(0,0,0,0.3) !important;
        border:0 !important;
        color:white !important;
        height: 60px !important;
        overflow-y: auto;
    }
        .type_msg:focus{
         box-shadow:none !important;
       outline:0px !important;
    }
    .attach_btn{
        border-radius: 15px 0 0 15px !important;
        background-color: rgba(0,0,0,0.3) !important;
        border:0 !important;
        color: white !important;
        cursor: pointer;
    }
    .emoji_picker{
        background-color: rgba(0,0,0,0.3) !important;
        border:0 !important;
        color: white !important;
        cursor: pointer;        
    }
    .send_btn{
        border-radius: 0 15px 15px 0 !important;
        background-color: rgba(0,0,0,0.3) !important;
        border:0 !important;
        color: white !important;
        cursor: pointer;
    }
    .search_btn{
        border-radius: 0 15px 15px 0 !important;
        background-color: rgba(0,0,0,0.3) !important;
        border:0 !important;
        color: white !important;
        cursor: pointer;
    }
    .contacts{
        list-style: none;
        padding: 0;
    }
    .contacts li{
        width: 100% !important;
        padding: 5px 10px;
        margin-bottom: 15px !important;
    }
    .active{
            background-color: rgba(0,0,0,0.3);
    }
    .user_img{
        height: 70px;
        width: 70px;
        border:1.5px solid #f5f6fa;
    
    }
    .user_img_msg{
        height: 40px;
        width: 40px;
        border:1.5px solid #f5f6fa;
    
    }
    .img_cont{
            position: relative;
            height: 70px;
            width: 70px;
    }
    .img_cont_msg{
            height: 40px;
            width: 40px;
    }
    .online_icon{
        position: absolute;
        height: 15px;
        width:15px;
        background-color: #4cd137;
        border-radius: 50%;
        bottom: 0.2em;
        right: 0.4em;
        border:1.5px solid white;
    }
    .offline{
        background-color: #c23616 !important;
    }
    .user_info{
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 15px;
    }
    .user_info span, .online_title span{
        font-size: 20px;
        color: white;
    }
    .user_info p{
    font-size: 10px;
    color: rgba(255,255,255,0.6);
    }
    .video_cam{
        margin-left: 50px;
        margin-top: 5px;
    }
    .video_cam span{
        color: white;
        font-size: 20px;
        cursor: pointer;
        margin-right: 20px;
    }
    .msg_cotainer{
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 10px;
        border-radius: 25px;
        background-color: #dd8282;
        padding: 10px;
        position: relative;
    }

    .man {
        background-color: #82ccdd !important;
    }
    .msg_cotainer_send{
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 10px;
        border-radius: 25px;
        background-color: #dd8282;
        padding: 10px;
        position: relative;
    }
    .msg_time{
        position: absolute;
        left: 0;
        bottom: -15px;
        color: rgba(255,255,255,0.5);
        font-size: 10px;
    }
    .msg_time_send{
        position: absolute;
        right:0;
        bottom: -15px;
        color: rgba(255,255,255,0.5);
        font-size: 10px;
    }
    .msg_head{
        position: relative;
    }
    #action_menu_btn{
        position: absolute;
        right: 10px;
        top: 10px;
        color: white;
        cursor: pointer;
        font-size: 20px;
    }
    .action_menu{
        z-index: 1;
        position: absolute;
        padding: 15px 0;
        background-color: rgba(0,0,0,0.5);
        color: white;
        border-radius: 15px;
        top: 30px;
        right: 15px;
        display: none;
    }
    .action_menu ul{
        list-style: none;
        padding: 0;
    margin: 0;
    }
    .action_menu ul li{
        width: 100%;
        padding: 10px 15px;
        margin-bottom: 5px;
    }
    .action_menu ul li i{
        padding-right: 10px;
    
    }
    .action_menu ul li:hover{
        cursor: pointer;
        background-color: rgba(0,0,0,0.2);
    }
    @media(max-width: 576px){
        .contacts_card{
            margin-bottom: 15px !important;
        }
    }    
</style>

