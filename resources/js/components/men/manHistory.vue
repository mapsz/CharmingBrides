<template>
<div class="container man-history">
  <h1 class="m-4">History</h1>

  <ul>
    <li v-for="(his,k) in history" :key="k">
      {{his.created_at}}: {{his.value}} <b>{{his.name}}</b> 

    </li>
  </ul>

</div>
</template>

<script>
  export default {   
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    data(){return{
      history:[],
    }}, 
    mounted(){
      this.get();
    },    
    methods:{
      async get(){
        let l = this.loading('.man-history');
        let r = await this.ax('get','/profile/json/history');

        if(!r){
          this.hideLoading(l);
          return
        } 
        
        this.history = r;
        this.hideLoading(l);
        
      }
    },
  }
</script>
