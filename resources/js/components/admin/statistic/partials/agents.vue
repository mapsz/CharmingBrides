<template>
  <div class="container-fluid">
    <h1>Statistic</h1>
    <div class="row">
      <div class="col-2">
        <statistic-categories></statistic-categories>
      </div>
      <div class="col-10 statistics-agents">
        <h1>Agents</h1>
        <!-- Period -->
        <div class="row">
          <div class="input-group input-group-sm mb-3">
            <span class="pr-2">Period:</span>  
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">From</span>
            </div>
            <datepicker  
              v-model="from"
              :monday-first="true"
            ></datepicker>
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">To</span>
            </div>
            <datepicker  
              v-model="to"
              :monday-first="true"
            ></datepicker>
            <button @click="getAgents()" class="btn btn-sm btn-primary ml-2">Search</button>         
          </div>
        </div>       
        <!-- List -->
        <div class="row">
          <table class="table">
            <thead class="thead-light">
              <tr>
                <th>Agent</th>
                <th>Chats</th>
                <th>Amount</th>
                <th>Letters</th>
                <th>Amount</th>
                <th>Additional</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="agent in agents" :key="agent.id">
                <td>{{agent.name}}</td>
                <td></td>
                <td></td>
                <td>{{countLetters(agent.id)}}</td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>      
    </div>
    
    <!-- modal -->
    <div id="membership-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>   
          <div class="modal-body">       
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <!-- <th>Date</th>
                  <th>Client</th>
                  <th>Price</th>
                  <th>Balance</th>
                  <th>Expire</th> -->
                </tr>
              </thead>
              <tbody>
                <!--  -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> 

  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {  
  mixins: [ mMoreAxios, mNotifications, mLoading ],
  components: {
    Datepicker
  },  
  data(){return{
    agents:[],
    from:moment().subtract(1, 'months').format(),    
    to:moment().format(),       
  }},
  mounted() {
    this.getAgents();
  },
  methods:{
    async getAgents(){
      let l = this.loading('.statistics-agents');
      let r = await this.ax('get','/admin/statistic/agents/get',{
        from:moment(this.from).unix(),
        to:moment(this.to).unix()
      });
      if(!r){
        this.hideLoading(l);
        return false;
      }

      this.agents = r;

      this.hideLoading(l);
    },
    countLetters(id){
      let count = 0;
      let agent = this.agents.find(x => x.id == id);
      $.each( agent.girl, ( k, v ) => {
        count += v.user.letter.length;
      });

      return count;
    },

  }

}
</script>

<style>

</style>