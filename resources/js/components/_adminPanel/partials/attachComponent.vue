<template>
  <div>    
    <!-- Show Hide Buttons -->
    <div class="attach-buttons">
      <!-- Show -->
      <button 
        v-if="!attach" 
        @click="doShowAttach()" 
        class="attach-button btn btn-success"
      >
        Attach new {{pName.relation.s}}
      </button>
      <!-- Hide -->
      <button v-if="attach" @click="doHideAttach()" class="attach-show-list btn btn-primary">Show Attached {{pName.relation.m}}</button>
    </div>
    <!--Attach list -->
    <div v-if="attach" >
      <h4 class="pt-2">Attach New {{pName.relation.m}}</h4>
      <!-- search -->
      <div  class="attach-search">
        <div class="input-group my-3">
          <input v-model="search" @keyup="searchAttach()" type="text" class="form-control" placeholder="Search..." aria-label="Search...">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button">Search</button>
          </div>
        </div>
      </div>
      <!-- list -->
      <div class="attach-list">        
        <div v-if="isEmpty" class="empty">
          Nothing Found
        </div>
        <list-component 
          v-else
          :p-data="{columns:pColumns,data:data.toAttach}"
          :p-settings="listSettings"
          :p-route="pRoute"
          :p-recent="{attach:recentAttach}"
          @attach="doAttach"
        />      
      </div>
    </div>
  </div>
</template>

<script>
//@@@ attach componen сделать выше attached list

    // import TestMixin from '../mixins/test';
    export default {
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-attach','p-name','p-route','p-columns','p-settings'],
        mounted() {
            this.attach = this.pAttach;
            this.getRecent();
            // $_moreAxios.hello();
        },
        data(){
          return {
              //Settings
              route: this.pRoute['prefix'] + this.pRoute['r'], 
              //Attach
              attach:false,
              search:"",
              toAttach:false,
              recentAttach:[],
              //List
              data:{
                attached:[],
                toAttach:[],
              },
              listSettings:{
                attachAdd:true,
              },
          }
        },    
        computed: {
          isEmpty: function () {
            return (this.data.toAttach.length < 1) ? true : false;             
          },
        },               
        methods:{

          //Attach
          doShowAttach(){
            //
            this.attach = true;
            this.$emit('doShowAttach');
          },
          doHideAttach(){
            //
            this.attach = false;
            this.$emit('doHideAttach');
          },  
          async doDetach(id){
            //
          },
          async doAttach(id){
            //Set Loading
            let loading = this.showLoading('.attach-list #row'+id);

            //Data
            let modelId     = this.pSettings.subList;
            let targetId    = id;
            let targetName  = this.pName.relation.s;

            //Attach Query
            let attach = await this.ax('put','/'+this.route+'/attach',{targetId:targetId,modelId:modelId,targetName:targetName});

            //Error
            if(!attach){this.hideLoading(loading);return false;}  

            //Success
            //Update row
            let row = await this.ax('get','/'+this.pRoute.prefix+this.pName.relation.s+'/get/'+id, {columns:this.pColumns})

            //Error
            if(!row){this.hideLoading(loading);return false;}  

            //Find old row
            let i = this.data.toAttach.findIndex(x => x.id == id);
            
            //Update old row
            this.data.toAttach[i] = row;

            //add to attached list
            this.$emit('addAttached',row);

            //set recent attached
            this.recentAttach.push(id);

            //Stop loading
            this.hideLoading(loading);

            //Show Success
            this.showSuccess('Successfully attached');
          },
          //List
          async searchAttach(){
            //lenght less 2 chars
            if(this.search.length < 2) return false;
            //Start loading
            let l = this.showLoading('.attach-list');
            //Data
            let columns = JSON.stringify(this.pColumns);
            let search = this.search;
            //Search Query
            let searchData = await axios.get('/'+this.pRoute.prefix+this.pName.relation.s+'/search', {params:{columns:columns,search:search}})
              .then((r) => {
                if(r.data.error == 0){ //Success                
                  return r.data.data; 
                }else{                 // Error            
                  this.axiosErrors(r);
                  return false;
                }
              })
              .catch((error) => {this.axiosCatch(error);return false;});

            this.data.toAttach = searchData.data;
            //Stop loading
            this.hideLoading(l);
          },
          async getRecent(){

            //Data
            let columns = JSON.stringify(this.pColumns);

            //Search Query
            let recentRows = await axios.get('/'+this.pRoute.prefix+this.pName.relation.s+'/recent/get', {params:{columns:columns}})
              .then((r) => {
                if(r.data.error == 0){ //Success                
                  return r.data.data; 
                }else{                 // Error             
                  this.axiosErrors(r);
                  return false;
                }
              })
              .catch((error) => {this.axiosCatch(error);return false;});


            this.data.toAttach = recentRows.data;
          },

        },

    }

</script>
