<template>
    <div class="container  my-4">
      <div class="row">
        <div class="col"><h1>Membership</h1></div>
        <div class="col">
          <a style="float: right; font-size: 16pt;" href="/profile"><b>Profile</b></a>
        </div>
      </div>
      <div class="row py-5">
        <div 
          class="col-12 membership"
        >
          <div class="media">
            <img 
              v-if="membership.image && membership.image[0]"
              :src="'/'+membership.image[0]" 
              class="align-self-center mr-3" 
              :alt="membership.name"
            >
            <div class="media-body">   
              <h4 class="card-title text-uppercase">{{membership.name}}</h4>
              <p class="card-text">
                Price for Lady's reply:
                <b>{{membership.long_letter_price}}&#8364</b><br>
                1 minute chat:
                <b>{{membership.chat_price}}&#8364</b><br>
                Expire:
                <b>{{expire}}</b><br>
              </p>
              <p class="card-text">
                Balance: <b>{{membership.balance}}</b>&#8364<br>
              </p>        
            </div>          
          </div>
        </div>
      </div>
      <div class="row">
        <a href="/memberships">
          <button class="btn btn-success">UPGRADE MEMBERSHIP!</button>
        </a>
      </div>
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            membership:[],
          }
        },  
        computed:{
          expire:function(){
            if(!this.membership.pivot){
              return false;
            }

            let d = new Date(this.membership.pivot.created_at);

             
            d = d.setDate(d.getDate() + this.membership.period);

            return moment.unix(d / 1000).format("lll");
          },
        },                     
        mounted() {
          this.getMembership();
        },
        methods: {
        async getMembership(){
          let l = this.loading('.likedyou-list'); //@@@ loading

          let membership = await this.ax('get', '/memberships/current');


          // if(matches){
            this.membership = membership;
          // }

          this.load = false;

          this.hideLoading(l);
        },
        }
    }
</script>
