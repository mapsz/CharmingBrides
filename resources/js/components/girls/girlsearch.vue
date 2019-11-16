<template>
    <div class="container-fluid p-0">
      <div class="form-row align-items-center">         

        <!-- Custom Search -->
        <div class="col-auto">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><fa-icon icon="search"/></div>              
            </div>
            <input v-model="search.search" type="text" class="form-control" style="width:175px;" >
          </div>
        </div>

        <!-- Age -->
        <div class="col-auto">
          <div class="input-group mb-2">
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
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text">Location</div>
            </div>
            <select v-model="search.location" class="custom-select mr-sm-2">
              <option value="0" selected>Any</option>
              <option v-for="i in params.locations" :value="i">{{i}}</option>
            </select>
          </div>
        </div>

        <!-- Maritial Status -->
        <div class="col-auto">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text">Maritial Status</div>
            </div>
            <select v-model="search.maritial" class="custom-select mr-sm-2">
              <option value="0" selected>Any</option>
              <option value="1" selected>Never married</option>
              <option value="2" selected>Divorced</option>
              <option value="3" selected>Widowed</option>
            </select>
          </div>
        </div>

        <!-- Children -->
        <div class="col-auto">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text">Children</div>
            </div>
            <select v-model="search.children" class="custom-select mr-sm-2">
              <option value="99">Any</option>
              <option value="0">none</option>
              <option value="1">1 or less</option>
              <option value="2">2 or less</option>
              <option value="3">3 or less</option>
              <option value="4">4 or less</option>
              <option value="5">5 or less</option>
            </select>
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
              maritial:0,
              children:99,              
            },
            params:{
              maritials:[],
              locations:[],
            }

          }
        },
        watch: {
          search: {
            deep:true,
            handler:function(){this.doSearch();},
          },
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
            // let l = this.loading('.girls-list');

            // let r = await this.ax('get','/all/girl/search',{search:this.search});

            // if(!r) {this.hideLoading(l);return false;}

            this.$emit('doSearch',this.search);
            // this.hideLoading(l);

            // return r;
          }
        }
    }
</script>
