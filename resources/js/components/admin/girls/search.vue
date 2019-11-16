<template>
    <div class="container-fluid p-0">
      <div class="form-row align-items-center">         

        <!-- Custom Search -->
        <div class="col-auto">
          <div class="input-group input-group-sm mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><fa-icon icon="search"/></div>              
            </div>
            <input v-model="search.search" type="text" class="form-control" style="width:100px;" >
          </div>
        </div>

        <!-- Age -->
        <div class="col-auto">
          <div class="input-group input-group-sm mb-2">
            <!-- FROM -->
            <div class="input-group-prepend">
              <div class="input-group-text">Age</div>
            </div>
            <select v-model="search.ageFrom" class="custom-select">
              <option v-for="i in 100-18" :value="i+17">{{i+17}}</option>
            </select>
            <div class="input-group-prepend">
              <div class="input-group-text" style="border-left: 0px;">-</div>
            </div>
            <select v-model="search.ageTo" class="custom-select" >
              <option v-for="i in 100-18" :value="i+17">{{i+17}}</option>
            </select>            
          </div>
        </div>

        <!-- Location -->
        <div class="col-auto">
          <div class="input-group input-group-sm mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text">Location</div>
            </div>
            <select v-model="search.location" class="custom-select mr-sm-2">
              <option value="0" selected>Any</option>
              <option v-for="i in params.locations" :value="i">{{i}}</option>
            </select>
          </div>
        </div>

        <!-- Agents -->
        <div class="col-auto p-0">
          <div class="custom-control custom-checkbox my-1 mr-sm-2">
            <input v-model="search.agents" type="checkbox" class="custom-control-input" id="inAgents">
            <label class="custom-control-label" for="inAgents">Agents</label>
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
        search:{
          search:'',
          ageFrom:18,
          ageTo:99,
          location:0,   
          agents:false,         
        },
        params:{}
      }
    },
    mounted() {
      this.getParams();
    },
    methods: {
      async getParams(){
        let r = await this.ax('get','/parametrs/girl');
        if(!r) return false;
        this.params = r;
      },  
      async doSearch(){
        this.$emit('doSearch',this.search);
      },
    }
  }
</script>
