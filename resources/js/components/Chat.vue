<template>
        <div class="container-fluid h-100 py-2">

            <loading :loading="loading.screen"/>

            <div v-if="reconnect" class="reconnect">
              <div class="reconnect-container">
                <p class="text-danger"><b>Connection Lost!</b></p>
                <center>
                  <button class="btn btn-primary" @click="ReconnectOnline()">Reconnect</button>
                </center>
              </div>
            </div>

            <admin-chat 
                v-if="prop_user.man >= 3" 
                @onlineReset="onlineReset()"
                @selectUser="selectUser"
            />              
            <div class="row justify-content-center h-100">
                <!-- Online -->
                <div class="col-md-3 col-xl-3 chat">
                    <div class="card mb-sm-3 mb-md-0 contacts_card">
                        <div class="card-header">
                            <div class="online_title">
                                <span>{{user.man ? 'Girls' : 'Mans' }} Online</span>
                            </div>
                        </div>
                        <div class="card-body contacts_body">
                            <!-- List -->
                            <ul class="contacts">
                                <li v-for="onlineUser in onlineUsers" 
                                  v-bind:id="onlineUser.id" 
                                  @click="selectRoom(onlineUser.id)"
                                  v-bind:class="{'active': room && room.companion.id == onlineUser.id}">
                                    <div class="d-flex bd-highlight" >
                                        <div class="img_cont">
                                            <img 
                                                :src = "assets+'/media/gallery/'+onlineUser.id+'_0.jpg'"
                                                class="rounded-circle user_img">
                                            <span class="online_icon"></span>
                                        </div>
                                        <div class="user_info">
                                            <span>{{onlineUser.name}} {{onlineUser.surname}}</span>
                                            <p>{{onlineUser.name}} is online</p>
                                        </div>
                                    </div>
                                </li>
                              <!--  <li>
                                    <div class="d-flex bd-highlight">
                                        <div class="img_cont">
                                            <img src="https://2.bp.blogspot.com/-8ytYF7cfPkQ/WkPe1-rtrcI/AAAAAAAAGqU/FGfTDVgkcIwmOTtjLka51vineFBExJuSACLcBGAs/s320/31.jpg" class="rounded-circle user_img">
                                            <span class="online_icon offline"></span>
                                        </div>
                                        <div class="user_info">
                                            <span>Sahar Darya</span>
                                            <p>Sahar left 7 mins ago</p>
                                        </div>
                                    </div>
                                </li> -->
                            </ul>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <!-- Chat -->
                <div class="col-md-6 col-xl-6 chat">
                    <div class="card">
                        <div style="display:none;" class="loading freeze">
                            <center><button class="btn btn-primary m-4" @click="startPayedChat()">Start Chat</button></center>
                        </div>  
                        <div v-show="loading.room" class="loading">
                            <span class="p-2" style="color:white">Loading...</span>
                        </div>  
                        <!-- Header -->
                        <div class="card-header msg_head">
                            <div v-if="room" class="d-flex bd-highlight">
                                <!-- Avatar -->
                                <div class="img_cont">
                                    <img 
                                        :src = "photoPath(room.companion.id)"
                                        class="rounded-circle user_img">
                                    <span v-bind:class="{'offline': !isOnline(room.companion.id)}" class="online_icon"></span>
                                </div>
                                <!-- Info -->
                                <div class="user_info">
                                    <span>
                                        {{room.companion.name}} {{room.companion.surname}}
                                    </span>
                                    <p>{{room.id}} - room</p>
                                </div>
                                <!-- Balance -->
                                <div v-if="user.man" class="user_info">
                                    <span>
                                        Balance: {{user.membership.balance}}$
                                    </span>
                                    <p>1  minute - {{user.membership.chat_price}}$  {{user.membership.name}}</p>
                                </div>
                                <!-- Timer -->
                                <div v-if="user.man" class="user_info">
                                    <span>
                                       {{timer.time}}
                                    </span>
                                    <p>Total: - {{timer.total}}$</p>
                                </div>    
                                <!-- Stop     -->
                                <div v-if="user.man" class="user_info">
                                       <button class="btn btn-primary" @click="stopPayedChat()">Stop</button>
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
                                    v-bind:class="{'justify-content-start': message.user_id == user.id,
                                                    'justify-content-end' : message.user_id != user.id }"
                                    class="d-flex mb-4">

                                    <img 
                                      v-if="message.user_id == user.id"
                                      :src="assets+'/media/gallery/'+message.user_id+'_0.jpg'"
                                      class="rounded-circle user_img_msg">

                                    <div 
                                        v-bind:class="{
                                            'man'  : 
                                                (message.user_id == user.id && user.man) ||
                                                (message.user_id == room.companion.id && room.companion.man),
                                            'msg_cotainer'      : message.user_id == user.id,
                                            'msg_cotainer_send' : message.user_id != user.id
                                        }"
                                        >
                                        {{message.body}}
                                        <span 
                                            v-bind:class="{'msg_time'      : message.user_id == user.id,
                                                           'msg_time_send' : message.user_id != user.id}"
                                            >                                       
                                            {{message.created_at}}
                                        </span>
                                    </div>

                                    <img 
                                        v-if="message.user_id != user.id"
                                        :src="assets+'/media/gallery/'+message.user_id+'_0.jpg'"
                                        class="rounded-circle user_img_msg">

                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="card-footer">
                            <!-- Chat -->
                            <!-- <div class="freeze"></div> -->
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
                    <div class="card-header">
                        <div class="online_title">
                            <span>Recent chats</span>
                        </div>
                    </div>
                    <div class="card-body contacts_body">
                        <!-- recent chat list -->
                        <ul  class="contacts">

                            <li       
                                v-for="r in recentRooms"
                                @click="selectRoom(r.companion.id)"                               
                                v-bind:class="{'active': room.id == r.id}">
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                         <img 
                                            :src = "assets+'/media/gallery/'+r.companion.id+'_0.jpg'"
                                            class="rounded-circle user_img">   
                                        <span 
                                            v-bind:class="{'offline': !isOnline(r.companion.id)}"
                                            class="online_icon">                        
                                        </span>
                                    </div>
                                    <div class="user_info">
                                        <span>{{r.companion.name}}</span>
                                        <p>
                                            {{r.companion.name}} is 
                                            {{isOnline(r.companion.id)?'online':'offline'}}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
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

     // Websockets
    import Echo from 'laravel-echo'
    window.Pusher = require('pusher-js');
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        // encrypted: true,
        wsHost: window.location.hostname,
        wsPort: 6001,
        disableStats: true,
    });

    import VueChatScroll from 'vue-chat-scroll';

    // Font Awsome   
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    Vue.component('fa-icon', FontAwesomeIcon); 
    import { library } from '@fortawesome/fontawesome-svg-core';    
    Vue.config.productionTip = false;
    //icons
    import { faArrowRight } from '@fortawesome/free-solid-svg-icons';
    library.add(faArrowRight);
    import { faPaperclip } from '@fortawesome/free-solid-svg-icons';
    library.add(faPaperclip);
    import { faSmile } from '@fortawesome/free-solid-svg-icons';
    library.add(faSmile);

    //Emoji picker
    import { Picker } from 'emoji-mart-vue';
    Vue.component('picker', Picker);

    export default {
        components: {
            VueChatScroll, 
            d3,
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
                timer: {
                        d3: d3.interval((elapsed) => {/**/},99999),
                        time:'0:00',
                        total:'0:00'
                    },
                messages:[],
                onlineUsers: [],
                recentRooms: [],
                reconnect:false,
                chatOnlineConnection:{},
                chatPrivateConnection:{},
                emojiPickerShow:false,
                //files
                assets:assets,          
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
                //Freeze size
                let headHeight = $('.msg_head').outerHeight();
                let bodyHeight = $('.chat').outerHeight();
                let newHeight = bodyHeight - headHeight;
                $('.freeze').outerHeight(newHeight+'px');
                $('.freeze').css('margin-top',headHeight);
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
            this.getUserMembership(this.user.id)
        },
        methods: {

            //Emoji
            addEmoji(emoji){
                $('.type_msg').val($('.type_msg').val() + emoji.native); //@@@ between text
            },
            //@@@ close on blank click

            //Online
            async setChatOnline(){
                //Get connection
                let c = this.chatOnlineConnection;
                //Refresh
                let chatOnline = false;
                //Debug
                // this.debug(c.hasOwnProperty('subscription') +' '+c.subscription.hasOwnProperty('subscribed')+' '+c.subscription.subscribed);
                // console.log(c);
                console.log('sub - '+c.subscription.subscribed +' pend - '+c.subscription.subscriptionPending);
                
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
                let c = await window.Echo.join(`chat`)
                    .here((users) => {
                        this.setOnlineUsers(users);                                     
                    })
                    .joining((user) => {
                        this.setOnlineUsers(user);
                    })
                    .leaving((user) => {
                        this.setOnlineUsers(user,true);
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
                await window.Echo.leave('chat');
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
                // console.log(rooms);
                this.recentRooms = rooms;
            },
            async getRecentRoom(){
               var r = await axios.get('/chat/recentRooms')
                    .then((r) => {
                        if(!r.data.error){
                            return r.data.rooms;                           
                        }else{
                            this.showErrorMsg({message:r.data.text});
                            return false;
                        }
                    })
                    .catch((r) => {return false;});

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
                    path+='/zoom/'+_user.id+'_0.jpg';
                    return path;
                }else{
                    let error = 'error getting user '+id+' path'
                    this.showErrorMsg({message:error})
                    return false;
                }
            },
            setOnlineUsers(users,del){

                // @@@

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
                        if(del){
                            this.onlineUsers.splice(this.onlineUsers.indexOf(users), 1);
                        }else{
                          if(user.man != this.user.man)                   
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

                var r = await this.ax('get','/memberships/current',{user_id: userId});

                //Set membership
                this.user.membership = {};
                if(r){
                    this.user.membership = r;
                }else{
                    this.user.membership.name = 'none';
                }

                return true;
            },

            // Private Room
            async roomHandler(val, oldVal){
                //Exit room
                if(!val) return;                

                //Set room loading
                this.loading.room = true;

                //Set messages
                await this.getMessages();

                //Connect
                if(!this.user.man || (val.connect)){
                    //Connect room
                    await this.connectRoom();
                    //Check connect
                    // @@@
                    //Start timer
                    if(this.user.man) this.startRoomTimer();

                    this.loading.room = false;
                    return;
                }

                //Freeze room                
                this.stopPayedChat();
                this.loading.room = false;
                return;
            },
            async selectRoom(companionId){
                this.loading.room = true;
                this.disconnectRoom();
                this.unFreezeRoom();

                //Get Room
                var room = await this.getRoom(companionId);
                if(!room){
                    let error = 'error getting room';
                    this.showErrorMsg({message:error});
                    this.loading.room = false;
                    return false; //@@@ add some error
                } 

                //Set room
                room.connect = false;
                this.room = room;
            },
            async connectRoom(){
                let c = await window.Echo.join('privateChat.' + this.room.id) //@@@ private chat
                    .listen('PrivateChat', ({message}) => {
                        this.messages.push(message);
                    })

                //Save connection params
                this.chatPrivateConnection = c;  

                return;                        
            },
            async disconnectRoom(){
                //Exit if no room
                this.chatPrivateConnection = {};
                if(!this.room) return;
                //Disconncet room
                let l = await window.Echo.leave('privateChat.' + this.room.id);           
                //Remove room
                this.room.connect = false;

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
            //Payed chat
            startPayedChat(){
                this.unFreezeRoom();
                this.room.connect = true;
            },
            stopPayedChat(){
                this.freezeRoom();
                this.stopRoomTimer(); 
                this.disconnectRoom();              
                this.room.connect = false;
            },
            startRoomTimer(){
                this.unFreezeRoom();
                this.timer.d3 = d3.interval((elapsed) => {

                    //Timer
                    let seconds = parseInt(elapsed / 1000);
                    let minutes = parseInt(seconds / 60); 
                    let _seconds = seconds % 60;
                    this.timer.time = minutes+":"+_seconds;

                    //Total
                    let pricePerSecond = this.user.membership.chat_price / 60;
                    this.timer.total = (pricePerSecond * seconds).toFixed(2);
                    console.log(this.timer.time);

                    //Balance
                    // this.user.membership.balance = this.user.membership.balance - this.timer.total;
                    //@@@
                    
                }, 999);
            },
            stopRoomTimer(){
                this.timer.d3.stop();
                this.timer.time = "0:00";
                this.timer.total = "0:00";
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

                //Post message
                axios.post('/chat/messages', {
                        'userId':this.user.id,
                        'roomId':this.room.id,
                        'body': message
                    })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            this.showErrorMsg({message:r.data.text});
                            return false;
                        }
                    })
                    .catch((r) => {return false;});
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

<style scoped>
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
        background-color: #00082eeb !important;
        border: 1px solid black !important;
        border-radius: 0px 0px 10px 10px !important;        
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

