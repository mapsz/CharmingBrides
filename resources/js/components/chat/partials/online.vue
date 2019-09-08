<template>
  <div class="col-md-3 col-xl-3 chat">
    <div class="card mb-sm-3 mb-md-0 contacts_card">
      <div class="card-header">
        <div class="online_title">
          <span>{{pUser.man ? 'Girls' : 'Mans' }} Online</span> 
          <fa-icon icon="link" 
            :class="{
              'text-success'      : pConnection.subscribed,
              'text-warning'      : pConnection.pending,
              'text-danger'       : pConnection.canceled
          }"  
          />
        </div>
      </div>
      <div class="card-body contacts_body">
        <!-- List -->
        <ul class="contacts">
          <li v-for="onlineUser in online" 
            v-bind:id="onlineUser.id" 
            @click="$emit('selectRoom',onlineUser.id)"
            v-bind:class="{'active': pCompanion && pCompanion.id == onlineUser.id}"
          >
            <div class="d-flex bd-highlight" >
              <div class="img_cont">
                <img 
                  :src = "assets+'/media/gallery/'+onlineUser.id+'_0.jpg'"
                  class="rounded-circle user_img"
                >
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
</template>

<script>
    export default {        
        props:['p-user','p-online-users','p-companion','p-connection'],
        data(){
          return {
            assets:assets,
          }
        },
        computed:{
          online:function(){
              let online = [];

              $.each(this.pOnlineUsers, (i, v) => {
                //Mans add
                if(v.man === 1 && this.pUser.man !== 1)
                  online.push(v);

                //Girls add
                if(v.man !== 1 && this.pUser.man === 1)
                  online.push(v);                
              });


              return online;

          }, 
        }       
    }
</script>
