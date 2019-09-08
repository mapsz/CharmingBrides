<template>
  <div class="container-fluid">
    <div class="card-header">
      <div class="online_title">
        <span>Recent chats</span>
      </div>
    </div>
    <div class="card-body contacts_body">
      <!-- recent chat list -->
      <ul  class="contacts">
        <li       
          v-for="r in pRecentRooms"
          @click="$emit('select-room',r.companion.id)"                            
          v-bind:class="{'active': pRoom.id == r.id}"
        >
          <div class="d-flex bd-highlight">
            <div class="img_cont">
               <img 
                :src = "assets+'/media/gallery/'+r.companion.id+'_0.jpg'"
                class="rounded-circle user_img">   
              <span 
                v-bind:class="{'offline': !pOnlineUsers.some(e => e.id == r.companion.id)}"
                class="online_icon">                        
              </span>
              <span v-if="!r.read" class="new_message_icon"></span>
            </div>
            <div class="user_info">
              <span>{{r.companion.name}}</span>
              <div v-if="payedChatTimer(r.id)">
                <p>
                  Active Chat: {{payedChatTimer(r.id)}}  
                </p>
              </div>
              <div v-else>
                <p>
                  {{r.companion.name}} is 
                  {{pOnlineUsers.some(e => e.id == r.companion.id)?'online':'offline'}}
                </p>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
    export default {        
        props:['p-recent-rooms','p-room','p-online-users','p-payed-chat'],
        data(){
          return {
            assets:assets,
          }
        },
        methods: {
          payedChatTimer(roomId){
            if(this.pPayedChat.lenght < 1) return false;
            let i = this.pPayedChat.findIndex(x => x.room == roomId);
            if(i > -1){   
              return this.pPayedChat[i].captionTime;
            }      

            return false;  
          }
        }
    }
</script>


<style scooped>
  
.new_message_icon {
  position: absolute;
  height: 22px;
  width: 22px;
  background-color: orange;
  border-radius: 50%;
  bottom: 0.2em;
  right: 50px;
  border: 1.5px solid white;
}

</style>