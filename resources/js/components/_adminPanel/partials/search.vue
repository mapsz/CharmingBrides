<template>
    <div class="container-fluid p-0">
      <div class="form-row align-items-center">       

        <div v-for="s in search" class="mr-2 ">

          <!-- Input text -->
          <div v-if="s.type == 'inputText'" class="input-group input-group-sm mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><fa-icon icon="search"/></div>              
            </div>
            <input @keyup="doSearch()" v-model="toSearch[s.name]" type="text" class="form-control" style="width:100px;" >
          </div>

          <!-- From to -->
          <div v-if="s.type == 'fromTo'" class="">
            <div class="input-group input-group-sm mb-2">
              <!-- FROM -->
              <div class="input-group-prepend">
                <div class="input-group-text text-capitalize">{{s.caption}}</div>
              </div>
              <select @change="doSearch()" v-model="toSearch[s.fromName]" class="custom-select">
                <option v-for="i in s.to-(s.from-1)" :value="i+(s.from-1)">{{i+(s.from-1)}}</option>
              </select>
              <!-- TO -->
              <div class="input-group-prepend">
                <div class="input-group-text" style="border-left: 0px;">-</div>
              </div>
              <select @change="doSearch()" v-model="toSearch[s.toName]" class="custom-select" >
                <option v-for="i in s.to-(s.from-1)" :value="i+(s.from-1)">{{i+(s.from-1)}}</option>
              </select>            
            </div>
          </div>   

          <!-- Select -->
          <div v-if="s.type == 'select'" class="">
            <div class="input-group input-group-sm mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text text-capitalize">{{s.caption}}</div>
              </div>
              <select @change="doSearch()" v-model="toSearch[s.name]" class="custom-select mr-sm-2">
                <option value="0" selected>Any</option>
                <option v-for="i in params[s.name]" :value="i">{{i}}</option>
              </select>
            </div>
          </div>     

          <!-- Checkbox -->
          <div v-if="s.type == 'checkbox'" class="p-0">
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input @change="doSearch()" v-model="toSearch[s.name]" type="checkbox" class="custom-control-input" :id="s.name+'inputField'">
              <label class="custom-control-label text-capitalize" :for="s.name+'inputField'">{{s.caption}}</label>
            </div>
          </div>            

        </div>


      </div>
    </div>
</template>

<script>
  export default {  
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    props:['p-search','p-param-route'],
    data(){
      return {        
        params:[],
        search:[],
        toSearch:{
        },
      }
    },
    async mounted() {
      console.log('search');
      console.log(this.pSearch);

      await this.getParams();

      this.search = this.pSearch;
      $.each(this.search, (i, v) => {
        //Set defaults
        if(v.def != undefined){
          this.toSearch[v.name] = v.def;
        }else{
          this.toSearch[v.name] = "";
        }
        if(v.fromDef  != undefined){
          this.toSearch[v.fromName] = v.fromDef;
        }
        if(v.toDef  != undefined){
          this.toSearch[v.toName] = v.toDef;
        }
        //Set Captions
        if(v.caption == undefined){
          this.search[i].caption = v.name;
        }
      });

      this.$emit('doSearch',this.toSearch);
    },
    methods: {
      async getParams(){
        let r = await this.ax('get',this.pParamRoute);
        if(!r) return false;
        this.params = r;
        return true;        
      },  
      async doSearch(){
        this.$emit('doSearch',this.toSearch);
      },
    }
  }
</script>
