<template>
    <div>
      <div v-if="email" class="email-confirm">
        Please verify your email address 
        <a href="/email/verify">
          <button class="btn btn-primary">Verify Email</button>
        </a>
      </div>
      <div id="noty-setup-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Notifications Setup</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Please let us know and indicate if you want to receive the notifications from the website (letters, signs of interest and messages from the administrators). In case you do want, please tick the appropriate boxes.</p>
            </div>
            <div class="modal-footer">
              <a href="/profile/notifications">
                <button type="button" class="btn btn-primary">To Settings</button>
              </a>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row','p-attr'],
        data(){
          return {
            setup:false,
            email:false,
          }
        },
             
        mounted() {
          this.getNoty();
          this.getVerified();
        },
        methods: {
          async getNoty(){
            let r = await this.ax('get','/sets/notifications');

            if(r == -1){
              $('#noty-setup-modal').modal('show');
            }
          },
          async getVerified(){
            let r = await this.ax('get','/verified/notifications');

            if(r == -1){
              this.email = true;
            }
          }
        }
    }
</script>


<style scooped>
  
.email-confirm {
  padding: 10px;
  background-color: #bf005a54;
  border: solid #bf005a 1px;
  text-align: center;  
}

</style>