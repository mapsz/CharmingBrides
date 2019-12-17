<template>
    <div class="container-fluid">
      <h1 class="m-3">Notifications</h1>

      <p>Please let us know and indicate if you want to receive the notifications from the website (letters, signs of interest and messages from the administrators). In case you do want, please tick the appropriate boxes.</p>

      <div class="row m-3 notifications-cotrol">
        <!-- News -->
        <div class="col-4">
          <h3>News</h3>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input v-model="noty.news.pushup" @change="setNotifications()" class="form-check-input" type="checkbox" id="news-pushup">
              <label class="form-check-label" for="news-pushup">Pushup</label>
            </div>
            <div class="form-check form-check-inline">
              <input v-model="noty.news.email" @change="setNotifications()" class="form-check-input" type="checkbox" id="news-email">
              <label class="form-check-label" for="news-email">Email</label>
            </div>
          </div>
        </div>

        <!-- Letters -->
        <div class="col-4">
          <h3>Letters</h3>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input v-model="noty.letters.pushup" @change="setNotifications()" class="form-check-input" type="checkbox" id="letters-pushup">
              <label class="form-check-label" for="letters-pushup">Pushup</label>
            </div>
            <div class="form-check form-check-inline">
              <input v-model="noty.letters.email" @change="setNotifications()" class="form-check-input" type="checkbox" id="letters-email">
              <label class="form-check-label" for="letters-email">Email</label>
            </div>
          </div>
        </div>
  
        <!-- Signs -->
        <div class="col-4">
          <h3>Signs of Interest</h3>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input v-model="noty.signs.pushup" @change="setNotifications()" class="form-check-input" type="checkbox" id="signs-pushup">
              <label class="form-check-label" for="signs-pushup">Pushup</label>
            </div>
            <div class="form-check form-check-inline">
              <input v-model="noty.signs.email" @change="setNotifications()" class="form-check-input" type="checkbox" id="signs-email">
              <label class="form-check-label" for="signs-email">Email</label>
            </div>
          </div>
        </div>
      </div>

    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            noty:{
              news:{
                pushup:0,
                email:0,
              },
              letters:{
                pushup:0,
                email:0,
              },
              signs:{
                pushup:0,
                email:0,
              },
            }
          }
        },     
        async mounted() {
          this.getNotifications();          
        },
        methods: {
          async getNotifications(){
            //Show loading
            let l = this.loading('.notifications-cotrol');

            //Get membership
            let noty = await this.ax('get','/men/notifications');
            //Error
            if(!noty){
              this.hideLoading(l);
              return;
            }

            this.noty = noty;

            this.setPushup();
            //Stop Loading
            this.hideLoading(l);
            return;
          },
          async setNotifications(){
            //Show loading
            let l = this.loading('.notifications-cotrol');

            //Send Noty  
            let r = await this.ax('post','/men/notifications',{noty:this.noty});
            this.hideLoading(l);

            //Get noties
            await this.getNotifications();

            
          },
          async setPushup(){

            let p = 0;
            $.each(this.noty, function(index, val) {
              if(val.pushup == 1){
                p = 1;
              }
            });

            if(p == 0) return;

            //Browser check
            if(!('Notification' in window)) return;

            //Granted , denied
            if(window.Notification.permission != 'granted') return;
              window.Notification.requestPermission();    
          }
        }
    }
</script>
