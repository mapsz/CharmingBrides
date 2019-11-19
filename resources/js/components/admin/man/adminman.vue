<template>
  <div class="container my-5 p-3" style="background-color: #ffc80057;
    border-radius: 10px;">
      <!-- Example -->
      <h2>Admin Info</h2>
      <div class="col-6">
        <div class="row">
          <span class="col-4"><b>Balance:</b></span>
          <span class="col-8">{{membership.balance}}</span>
          <span class="col-4"><b>Membership:</b></span>
          <span class="col-8">{{membership.name}}</span>
          <span class="col-12 py-2"><admin-man-login :p-row="{id:pId}"> </admin-man-login></span>
        </div>
      </div>
  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-id'],
        data(){
          return {
            membership:{
              balance:'0.00',
              name:'',

            },
          }
        },
        mounted() {
          this.getMembership();
        },        
        methods: {
          async getMembership(){
            let r = await this.ax('get','/memberships/current',{user_id:this.pId});

            this.membership = r;
          }
        }
    }
</script>
