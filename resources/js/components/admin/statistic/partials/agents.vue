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
        <div class="row" style="max-width:400px">
          <div class="input-group input-group-sm mb-3">
            <span class="pr-2">Period:</span>  
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">From</span>
            </div>
            <input type="text" class="form-control">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">To</span>
            </div>
            <input type="text" class="form-control"> 
            <button class="btn btn-sm btn-primary ml-2">Search</button>         
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
  </div>
</template>

<script>
export default {
  mixins: [ mMoreAxios, mNotifications, mLoading ],
  data(){return{
    agents:[],

    
  }},
  mounted() {
    this.getAgents();
  },
  methods:{
    async getAgents(){
      let l = this.loading('.statistics-agents');
      let r = await this.ax('get','/admin/statistic/agents/get');
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
    }
  }

}
</script>

<style>

</style>