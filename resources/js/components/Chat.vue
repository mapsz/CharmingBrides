<template>
        <div class="container-fluid h-100 py-2">
                
            <loading :loading="loading"></loading>

            <admin-chat 
                v-if="prop_user.man >= 3" 
                @onlineReset="onlineReset()"
                @selectUser="selectUser"
                @leaveRoom="leaveRoom"
            >                        
            </admin-chat>

            <button @click="startRoomTimer()">test</button>

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
                                                :src = "photoPath(onlineUser.id)"
                                                class="rounded-circle user_img">
                                            <span class="online_icon"></span>
                                        </div>
                                        <div class="user_info">
                                            <span>{{onlineUser.name}} {{onlineUser.surname}}</span>
                                            <p>{{onlineUser.name}} is online</p>
                                        </div>
                                    </div>
                                </li>
                  <!--               <li>
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
                                        Chat with {{room.companion.name}} {{room.companion.surname}}
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
                            <div v-show="freeze" class="freeze">
                                <center>
                                    <button class="btn btn-primary m-4" @click="startPayedChat()">Start Chat</button>
                                </center>
                            </div>
                            <div v-for="message in messages">
                                <!-- Messages -->

                                <div 
                                    v-bind:class="{'justify-content-start': message.user_id == user.id,
                                                    'justify-content-end' : message.user_id != user.id }"
                                    class="d-flex mb-4">

                                    <img 
                                      v-if="message.user_id == user.id"
                                      :src="photoPath(message.user_id)"
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
                                        :src="photoPath(message.user_id)"
                                        class="rounded-circle user_img_msg">

                                </div>

                                <!-- right message -->
                      <!--           <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Hi Maryam i am good tnx how about you?
                                        <span class="msg_time_send">8:55 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="https://2.bp.blogspot.com/-8ytYF7cfPkQ/WkPe1-rtrcI/AAAAAAAAGqU/FGfTDVgkcIwmOTtjLka51vineFBExJuSACLcBGAs/s320/31.jpg" class="rounded-circle user_img_msg">
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="card-footer">
                            <!-- Chat -->
                            <!-- <div class="freeze"></div> -->
                            <div class="input-group">
                                <!-- Attach -->
                                <!-- <div class="input-group-append">
                                    <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                                </div> -->
                                <!-- Send message -->
                                <!-- Text body -->
                                <textarea 
                                    v-on:keyup.enter="sendMessage()" 
                                    v-model="message" 
                                    name="text" 
                                    class="form-control type_msg" 
                                    placeholder="Type your message..."
                                ></textarea>
                                <!-- Send button -->
                                <div class="input-group-append" @click="sendMessage()">
                                    <span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></span>
                                </div>
                            </div>
                            <!-- No invites -->
                          <!--   <div 
                                v-if="room.userConfirm == 0 && room.companionConfirm == 0" 
                                class="input-group">
                                <button class="btn" @click="inviteCompanion()">Invite</button>
                            </div> -->
                            <!-- Invite from companion -->
                           <!--  <div 
                                v-if="room.userConfirm == 0 && room.companionConfirm == 1" 
                                class="input-group">
                                <span>You got invite!</span>
                                <button class="btn" @click="inviteCompanion()">Confirm</button>
                            </div> -->
                            <!-- Invite from user -->
                            <!--  <div 
                                v-if="room.userConfirm == 1 && room.companionConfirm == 0" 
                                class="input-group">
                                <span>You invited {{room.companion.name}}!</span>
                                <button class="btn" @click="">Cancel</button>
                            </div>     -->                       
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
                                                :src="photoPath(r.companion.id)" 
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
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import VueChatScroll from 'vue-chat-scroll'
    export default {
        components: {
            VueChatScroll, 
            d3,
        },
        props:['prop_user'],
        data(){
            return {
                loading: false,
                user: {},
                isDebug: false,
                room: false,
                connected: false,
                freeze:false,
                timer: {
                        d3,
                        time:'0:00',
                        total:'0:00'
                    },
                message: "",
                messages:[],
                onlineUsers: [],
                recentRooms: [],
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

            //Refresh timer
            this.refreshRoomTimer();
        },
        methods: {
            async joinOnline(){
                this.debug('online users');
                this.onlineUsers =[];
                
                let r = await window.Echo.join(`chat`)
                    .here((users) => {
                        this.setOnlineUsers(users);                                      
                        this.debug(users);                                        
                    })
                    .joining((user) => {
                        this.debug('joining - ' + user.email);
                        this.setOnlineUsers(user);
                    })
                    .leaving((user) => {
                        this.debug('leaving - ' + user.email);
                        this.setOnlineUsers(user,true);
                    });

                console.log(r);

            },
            async leaveOnline(){
                this.onlineUsers =[];
                await window.Echo.leave('chat');
                return true;
            },
            async onlineReset(){
                await this.leaveOnline();
                this.joinOnline();        
            },
            async selectRoom(companionId){

                //Exit current room
                if(typeof this.room.id !== 'undefined'){
                    this.leaveRoom();
                }

                //Stop prev room
                this.stopPayedChat();
                this.disconnectRoom();

                //Get Room
                var room = await this.getRoom(companionId);
                if(!room){
                    this.debug('error getting room');
                    return false; //@@@ add some error
                } 

                //Create room
                if(room >= 1){
                    this.debug('creating new room');
                    room = await this.storeRoom(companionId);
                }
                if(!room){
                    this.debug('error getting room');
                    return false; //@@@ add some error
                } 

                //Set room
                this.setRoom(room);

                //Connect Room
                if(!this.user.man){
                    this.connectRoom();
                }

                //Set messages
                this.getMessages();

                //Freeze chat                       
                this.freezeRoom();  
            },
            leaveRoom(){
                //Disconnect room
                this.disconnectRoom();
                //False room
                this.room = false;
                //Clear messages
                this.messages = [];
            },
            async getRoom(companionId){
                this.startLoading();
               var r = await axios.get('/room', {
                        params: {
                            'companionId':companionId,
                            'userId':this.user.id
                        }
                    })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error == 1){
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        if(r.data.noRoom){
                            this.debug(r.data.text);
                            return 2;
                        }

                        return r.data;
                    })
                    .catch((r) => {this.debug(r);return false;});

                this.stopLoading();
                return r;
            },            
            setRoom(room){
                this.room = room;
                this.room.counter = "0:00";        
                this.setChatConfirms();               
            },
            startPayedChat(){
                console.log('start payed chat')//@@@

                //@@@ start chat

                //Connect room                
                this.connectRoom();
                //Unfreeze
                this.unFreezeRoom();
                //Start timer
                this.startRoomTimer();
            },
            stopPayedChat(){
                //Disconnect room
                this.disconnectRoom();
                //Freeze
                this.freezeRoom();
                //Stop timer
                this.stopRoomTimer();
            },
            startRoomTimer(){
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
            refreshRoomTimer(){
                this.timer.d3 = d3.interval((elapsed) => {/*refresh */}, 10);
                this.stopRoomTimer();
            },
            freezeRoom(){
                $('.freeze').height(368); //@@@ dinamic height

                if(this.user.man)
                    this.freeze = true;
                else
                    this.freeze = false;     

                $('.freeze').height(368); //@@@ dinamic height           
            },
            unFreezeRoom(){

                this.freeze = false;
            },
            connectRoom(){
                window.Echo.join('privateChat.' + this.room.id) //@@@ private chat
                    .listen('PrivateChat', ({message}) => {
                        this.debug(message);
                        this.messages.push(message);
                    })                            
            },
            disconnectRoom(){
                let l = window.Echo.leave('privateChat.' + this.room.id);
                this.debug('privateChat.' + this.room.id);
            },
            setRoomCompanion(id){
                this.room.companion = this.onlineUsers.filter(x => x.id === id)[0];
                this.debug(this.room);
            },
            setChatConfirms(){
                //User man
                if(this.user.man){
                    //Man
                    this.room.userConfirm       = this.room.man_confirm;
                    this.room.companionConfirm  = this.room.girl_confirm;
                }else{
                    //Girl
                    this.room.userConfirm       = this.room.girl_confirm;
                    this.room.companionConfirm  = this.room.man_confirm;
                }
            },
            async storeRoom(companionId){
                this.startLoading();
                var r = await axios.post('/room', 
                        {
                            'companionId':companionId,
                            'userId':this.user.id
                        })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        return r.data;
                    })
                    .catch((r) => {this.debug(r);return false;});

                this.stopLoading();
                return r;
            },
            inviteCompanion(){
                // Check no companion
                if(!this.room.companion){
                    this.debug('no companion');
                    return false;
                }

                // Send invite
               axios.post('/chat/invite', {
                    'userId' : this.user.id,
                    'roomId' : this.room.id,
               })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        if(!r.data.room){
                            this.debug('bad room invites');
                        }

                        this.setRoom(r.data.room);
                    })
                    .catch((r) => {this.debug(r);return false;});
            },
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
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        if(!r.data.messages){
                            this.debug('no messages');
                        }

                        this.messages = r.data.messages;

                    })
                    .catch((r) => {this.debug(r);return false;});             
            },
            async sendMessage(){

                //Clear message
                let message = this.message;
                this.message = "";

                //Post message
                axios.post('/chat/messages', {
                        'userId':this.user.id,
                        'roomId':this.room.id,
                        'body': message
                    })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }
                    })
                    .catch((r) => {this.debug(r);return false;});
            },
            async setRecentRoom(){

                //Get rooms
                let rooms = await this.getRecentRoom();

                //Set rooms
                // console.log(rooms);
                this.recentRooms = rooms;

                this.debug(this.recentRooms);
            },
            async getRecentRoom(){
               var r = await axios.get('/chat/recentRooms')
                    .then((r) => {
                        if(!r.data.error){
                            return r.data.rooms;                           
                        }else{
                            this.debug(r.data.text)
                            return false;
                        }
                    })
                    .catch((r) => {this.debug(r);return false;});

                return r;                
            },
            isOnline(id){
                if (this.onlineUsers.some(e => e.id == id))
                    return true;

                return false;
            },
            photoPath(id){

                //Get user
                let user = false;

                do{
                    //Check in online
                    user = this.onlineUsers.find(x => x.id == id)
                    if(user){
                        break;
                    } 
                    
                    //Check in recent chats
                    user = this.recentRooms.find(x => x.companion.id == id);
                    if(user.hasOwnProperty('companion')){
                        break;
                    } 

                }while(false);

                
                if(user){
                    let path = 'media/';
                    user.man ? path+='mans' : path+='girls';
                    path+='/zoom/'+user.id+'_0.jpg';
                    return path;
                }else{
                    this.debug('error getting user '+id+' path');
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
                                        this.onlineUsers.push(u);
                                    }
                                }
                            })
                        }else{
                            if(typeof(user) === "object"){
                                if(del){
                                    this.onlineUsers.splice(this.onlineUsers.indexOf(user), 1);
                                }else{
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
                            this.onlineUsers.push(users);
                        }
                    }
                }
            },
            selectUser(user){
                this.user = user;
                this.leaveRoom();
            },
            async getUserMembership(userId){
                var r = await axios.get('/memberships/current', {
                                        params: {
                                          user_id: userId
                                        }
                  })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }
                        return r.data.membership;

                    })
                    .catch((r) => {console.log(r);return false;});   

                //Set membership
                if(r){
                    this.user.membership = r;
                }else{
                    this.user.membership.name = 'none';
                }

                return true;
            },
            startLoading(){

                this.loading = true;
            },
            stopLoading(){

                this.loading = false;
            },
            debug(x){
                if(this.isDebug){
                    console.log(x);
                }
            },
        }

    }
</script>

<style scoped>

    .chatContainer{
            height: 100%;
            margin: 0;
            background: #7F7FD5;
            background: -webkit-linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
            background: linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
    }
    .freeze {
        width: 100%;
        background-color: #000000bf;
        margin: -20px;
        position: absolute;
        z-index: 999;
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
