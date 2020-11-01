<template>  
<div class="container-fluid">
  <h1>Statistic</h1>    
  <div class="row">   

    <!-- Menu -->  
    <div class="col-2">
      <statistic-categories></statistic-categories>
    </div>

    <!-- Content -->
    <div class="col-10 statistics-agents">
      
      <h1>Services</h1>

      <!-- Period -->
      <div class="row">
        <div class="input-group input-group-sm mb-3">
          <!-- Period -->
          <div class="d-flex" style="align-items: center;">
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
          </div>
          <!-- Add -->
          <div class="d-flex">
            <!-- Modal button -->
            <button class="btn btn-sm btn-success ml-2" data-toggle="modal" data-target="#add-service-modal">Add New</button>
            
            <!-- Modal -->
            <div class="modal fade" id="add-service-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                    
                    <!-- Item -->
                    <div class="d-flex" style="justify-content: space-around;">
                      <!-- Items -->
                      <div class="form-group">
                        <select v-model="addServiceId" class="form-control" style="width: 200px;">
                          <option :value="0" selected>Choose item</option>
                          <option v-for="(item, i) in services" :key="i" :value="item.id">{{item.name}}</option>
                        </select>
                      </div>

                      <!-- Price -->
                      <div class="mx-2" style="width: 150px;">
                        Price: {{price}}
                      </div>

                      <!-- Paid to agent -->
                      <div class="form-group">
                        <input v-model="addPaidtoAgent" type="text" class="form-control" placeholder="Paid to Agent">
                      </div>
                    </div>

                    <!-- Users -->
                    <div class="d-flex" style="justify-content: space-around;">
                      <!-- Agent -->
                      <div class="form-group">
                        <select v-model="addAgentId" class="form-control">
                          <option>Choose agent</option>
                          <option v-for="(agent, i) in agents" :key="i" :value="agent.id">{{agent.name}}</option>
                        </select>
                      </div>               
                      
                      <!-- Girl -->
                      <div class="form-group mx-3">
                        <input v-model="addGirlId" type="number" class="form-control" placeholder="Girl id">
                      </div>

                      <!-- Man -->
                      <div class="form-group">
                        <input v-model="addManId" type="number" class="form-control" placeholder="Man id">
                      </div>
                    </div>

                    <!-- Date -->
                    <div class="d-flex">
                      <div class="form-group">                        
                        <datepicker  
                          v-model="addDate"
                          :monday-first="true"
                        ></datepicker>
                      </div>
                    </div>

                  </div>

                  <ul>
                    <li v-for="(error, i) in axiosErrors" :key="i" style="color:tomato">
                      {{error[0]}}
                    </li>

                  </ul>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button @click="addService()" type="button" class="btn btn-success">Confirm</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>  

      <!-- List -->
      <div class="row">
        <table class="table">
          <thead class="thead-light">
            <tr>
              <th>Date</th>
              <th>Item</th>
              <th>Agent</th>
              <th>Girl</th>
              <th>Man</th>
              <th>Price</th>
              <th>Paid to Agent</th>
            </tr>
          </thead>
          <tbody>  
            <tr v-for="(service, i) in statisticServices" :key="i" >
              <td>{{service.date}}</td>
              <td>{{service.service.name}}</td>
              <td>{{service.agent.id}} {{service.agent.agent.name}}</td>
              <td>{{service.girl.id}} {{service.girl.girl.name}}</td>
              <td>{{service.man.id}} {{service.man.man.name}}</td>
              <td>{{service.service.price}}</td>
              <td>{{service.paid_to_agent}}</td>
            </tr>
          </tbody>
        </table>
      </div>    


    </div>   

  </div>
</div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {
mixins: [ mMoreAxios, mNotifications, mLoading ],
components: {Datepicker},  
data(){return{
  addDate:moment().format(),
  addServiceId:0,
  addManId:"",
  addGirlId:"",
  addAgentId:"",
  addPaidtoAgent:"",
  addDate:"",
  from:moment().subtract(1, 'months').format(),    
  to:moment().format(),
  statisticServices:[],
  services:[],
  agents:[],
}},
computed:{
  price(){
    if(this.services.length == 0) return 'nope';
    if(this.addServiceId == 0) return 'nope';    

    let p = this.services.find(x => x.id == this.addServiceId);

    if(!p) return 'nope';
    if(p.price == undefined) return 'nope';

    return p.price;
  },
},
async mounted() {  
  this.getServices();
  this.getAgents();
  this.getStatisticServices();
},
methods:{
  async addService(){
      let l = this.loading('#add-service-modal');
      
      this.axiosErrors = [];

      // Data
      let data = {
        date : moment(this.addDate).format('YYYY/MM/DD'),
        serviceId : this.addServiceId,
        manId : this.addManId,
        girlId : this.addGirlId,
        agentId : this.addAgentId,
        price : this.addPrice,
        paidtoAgent : this.addPaidtoAgent,
      };

      let r = await this.ax('put','/admin/statistic/service',data);

      console.log(r);

      if(!r){
        this.hideLoading(l);
        return false;
      }

      location.reload();

      this.hideLoading(l);

  },
  async getStatisticServices(){
      let l = this.loading('#add-service-modal');
      let r = await this.ax('get','/admin/statistic/services/get');
      if(!r){
        this.hideLoading(l);
        return false;
      }

      this.statisticServices = r;

      this.hideLoading(l);
  },  
  async getServices(){
      let l = this.loading('#add-service-modal');
      let r = await this.ax('get','/admin/service/get');
      if(!r){
        this.hideLoading(l);
        return false;
      }


      this.services = r;

      this.hideLoading(l);
  },
  async getAgents(){
      let l = this.loading('#add-service-modal');
      let r = await this.ax('get','/admin/agent/get');
      if(!r){
        this.hideLoading(l);
        return false;
      }

      this.agents = r;

      this.hideLoading(l);
  }
},

}
</script>

<style>

</style>