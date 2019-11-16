<template>
    <div class="container-fluid list-container p-0">
      <!-- Attach -->
      <div v-if="isAttach" class="attach pb-4">
        <attach-component 
          :p-name="pName"
          :p-attach="attach"
          :p-route="pRoute"
          :p-columns="columns"
          :p-settings="pSettings"
          @doHideAttach="doHideAttach()"
          @doShowAttach="doShowAttach()"
          @addAttached="addAttached"
        />
      </div>
      <!-- Search -->
      <div v-if="pSettings && pSettings.search" class="input-group my-3">
        <juge-search
          :p-search="pSettings.search.search"
          :p-param-route="pSettings.search.paramsRoute"
          @doSearch="doSearch"
        ></juge-search>     
      </div>      
      <!-- List -->
      <div v-if="!attach" class="list"> 
        <table class="table table-striped">
          <thead class="bg-primary text-white">
            <!-- Captions -->
            <tr>
              <!-- Columns -->
              <th 
                v-for="column in columns"
                scope="col"
                class="text-capitalize"
                v-bind:class="{'sortable': true}"
                style="font-size:16pt"
                @click="doSort(column.name)"
              >
                {{column.caption}}
                <span v-if="sort.name == column.name">
                  <fa-icon v-if="sort.type == 'asc'" icon="arrow-up" class="pl-1" style="color:yellow"/>
                  <fa-icon v-if="sort.type == 'desc'" icon="arrow-down" class="pl-1" style="color:yellow"/>
                </span>
              </th>
              <!-- Edit -->
              <th scope="col" v-if="isEdit" style="font-size:16pt">Edit</th>
              <!-- Delete -->
              <th scope="col" v-if="isDelete" style="font-size:16pt">Delete</th>
              <!-- Attach -->
              <th scope="col" v-if="isAttachAdd" style="font-size:16pt">Attach</th>
              <!-- Detach -->
              <th scope="col" v-if="isDetach" style="font-size:16pt">Detach</th>
              <!-- Detach -->
              <th scope="col" v-if="isLink" style="font-size:16pt">More</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data -->
            <tr v-for='row in data' 
              :id="'row'+row.id" 
              :class="{
                'bg-success':(recent.add == row.id),
                'bg-warning':(recent.edit == row.id),
                'bg-info':(isRecentAttached(row.id)),
              }"
            >
              <!-- Columns -->
              <td v-for="key in columns">
                <!-- Custom components -->
                <span v-if="key.component">
                  <component :is="key.component" :p-attr="key.attr" :p-row="row"></component>
                </span>
                <!-- List -->
                <span v-else-if="typeof(key.list) == 'object'">
                  <div class="list-button">
                    <button @click="openList(row.id, key.name)" class="btn btn-info btn-sm">
                      Show {{key.name}} ({{row[key.name].length}})
                    </button>
                  </div>          
                </span>
                <!-- default value -->
                <span v-else class="caption">{{row[key.name]}}</span>
              </td>
              <!-- Edit -->
              <td v-if="isEdit" class="float">
                <a :href="'/'+route+'/edit/'+row._id">
                  <button type="button" class="btn btn-sm btn-warning border border-primary">Edit</button>
                </a>
              </td>
              <!-- Delete -->
              <td v-if="isDelete" class="float">
                <button data-toggle="modal" data-target="#delete-modal" @click="toDelete=row._id" type="button" class="btn btn-sm btn-danger border border-primary">Delete</button>
              </td>
              <!-- Attach -->
              <td v-if="isAttachAdd && !isRecentAttached(row.id)" class="float">
                <button @click="$emit('attach', row.id)" type="button" class="btn-attach btn btn-sm bg-attach border border-primary">Attach</button>
              </td>
              <!-- Detach -->
              <td v-if="isDetach" class="float">
                <button data-toggle="modal" data-target="#detach-modal" @click="toDetach=row.id" type="button" class="btn btn-sm bg-orange border border-primary">Detach</button>
              </td>
              <!-- Link -->
              <td v-if="isLink" class="float">
                <a :href="pSettings.link+row.id"><button type="button" class="btn btn-sm btn-primary">More</button></a>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- Paginator -->
        <div v-if="pages > 1"  class="row d-flex justify-content-center">        
          <pages :p-page="1" :p-pages="pages" @changePage="changePage"></pages>  
        </div>
        
      </div>
      <!-- List modal -->
      <div 
        v-if="listData"
        class="modal" 
        id="list-modal"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h4 class="modal-title text-capitalize">{{list.header}}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <list-component 
                :p-data="listData"
                :p-settings="listData.settings"
                :p-route="pRoute"
                :p-name="listName"
                :container-class="'pagination'"
                :page-class="'page-item'"              
              />
            </div>
          </div>
        </div>
      </div>   
      <!-- Delete modal -->
      <div 
        v-if="isDelete"
        class="modal" 
        id="delete-modal"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-danger">
              <h5 class="modal-title">Delete ({{toDelete}}) </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Are you sure?</p> <!-- show row data -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="deleteRow()">Delete</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Detach modal -->
      <div 
        v-if="isDetach"
        class="modal" 
        id="detach-modal"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-orange">
              <h5 class="modal-title">Detach ({{toDetach}}) </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Are you sure?</p> <!-- show row data -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn bg-orange" @click="detachRow()">Detach</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>

     // Font Awsome   
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    Vue.component('fa-icon', FontAwesomeIcon); 
    import { library } from '@fortawesome/fontawesome-svg-core';    
    Vue.config.productionTip = false;
    //icons
    import { faArrowDown } from '@fortawesome/free-solid-svg-icons';
    library.add(faArrowDown);   
    import { faArrowUp } from '@fortawesome/free-solid-svg-icons';
    library.add(faArrowUp);   
    import { faSearch } from '@fortawesome/free-solid-svg-icons';
    library.add(faSearch);   

    export default {  
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-data', 'p-settings', 'p-route', 'p-name', 'p-recent'],
        data(){
          return {
            //Data
            data:[],
            columns:[],
            page:1,
            recent:{
              edit:false,
              add:false,
              attach:[],
            },
            sort:{
              name:false,
              type:false,
            },
            search:{},
            //List
            list:{
              header: "",
            },
            listData:false,  
            listName:this.pName,
            //Actions
            toDelete:false,           
            toDetach:false,
            //Attach
            attach:false,
            //Setting
            route: "", 
            pages:1,
          }
        },
        computed: {
          isLink: function () {
            if (this.pSettings == undefined) return false;
            if(this.pSettings.link == undefined) return false;
            else return this.pSettings.link;
          },
          isEdit: function () {
            if (this.pSettings == undefined) return false;
            if(this.pSettings.edit == undefined) return false;
            else return this.pSettings.edit;
          },
          isDelete: function () {
            if (this.pSettings == undefined) return false;
            if(this.pSettings.delete == undefined) return false;
            else return this.pSettings.delete;
          },
          isDetach: function () {
            if (this.pSettings == undefined) return false;
            if(this.pSettings.detach == undefined) return false;
            else return this.pSettings.detach;
          },
          isAttach: function () {
            if (this.pSettings == undefined) return false;
            if(this.pSettings.attach == undefined) return false;
            else return this.pSettings.attach;
          },
          isAttachAdd: function () {
            if (this.pSettings == undefined) return false;
            if(this.pSettings.attachAdd == undefined) return false;
            else return this.pSettings.attachAdd;
          },
        },
        watch: {
          pData: {
            deep:true,
            handler:function(){this.updateData();},
          },
          pRecent:{
            deep:true,
            handler:function(){
              //@@@ через foreach
              if(this.pRecent.add      != undefined){this.recent.add      = this.pRecent.add}
              if(this.pRecent.edit     != undefined){this.recent.edit     = this.pRecent.edit}
              if(this.pRecent.attach   != undefined && this.pRecent.attach != false){
                if(this.recent.attach.indexOf(this.pRecent.attach) == -1){
                  this.recent.attach     = this.pRecent.attach;
                }
              }
            }
          }
        },        
        mounted() {
          let l = this.loading('.list-container');          
          this.pages = this.pSettings.pages;
          //Route
          if(this.pRoute != undefined){
            this.route = this.pRoute['prefix'] + this.pRoute['r'];
          }

          this.updateData();
          this.prepareSubList();
          this.queryStringHandle();

          //Recent atach
          if(this.pRecent != undefined){
            if(this.pRecent.attach != undefined){
              this.recent.attach = this.pRecent.attach;
            }
          }

          this.hideLoading(l);
        },
        methods:{
          //Attach
          addAttached(row){
            console.log(row);
            this.data.push(row);
            //
          },
          doShowAttach(){
            //   //
            this.attach = true;
          },
          doHideAttach(){
            //
            this.attach = false;
          },            
          isRecentAttached: function(id){
            if(this.recent.attach.indexOf(id) == -1){
              return false;
            }else{
              return true;
            }
          },
          //
          updateData(){
            this.data = this.pData.data;
            this.columns = this.pData.columns;
          },
          pageHandler(page){
            this.page = page;
            this.getData();
          },
          queryStringHandle(){
            let qs = queryString.parse(location.search);

            $.each(qs, (i, v) => {
              switch (i) {
                case 'page':
                    this.page = v;
                  break;
                case 'recentAdd':
                  //Инструкции, соответствующие value2
                  break;
                case 'recentEdit':
                  //Инструкции, соответствующие значению valueN
                  //statementsN
                  break;
              }
            });
          },
          async putSuccess(edit){
            //Refresh edit
            this.edit = false;
            //Hide modal
            $('#edit-modal').modal('hide');
            //Get updated data
            await this.getData();
            //Set edited row
            if(edit.work == "add"){
              // Set first
              // get row
              let row = this.data.filter(x => x.id == edit.id)[0];
              // remove row
              this.data.splice(this.data.findIndex(x => x.id == edit.id), 1);
              // set first
              this.data.unshift(row);
              this.recent.add = edit.id;
            }else{
              this.recent.edit = edit.edit;
            }            
          }, 
          //Data
          doSort(column){
            //Sort name
            this.sort.name = column;
            //Sort
            if(this.sort.type == "asc" && column == this.sort.name){
              this.sort.type = 'desc';
            }else{
              this.sort.name = column;
              this.sort.type = 'asc';
            }
            //Single page front sort
            if(this.pages <= 1){
              this.pageSort(column);
              return;
            }
            //Backend sort
            this.searchData();
            console.log(this.sort);
          },
          pageSort(column){
            if(this.sort.type == "desc" && column == this.sort.name){
              this.data.sort(function (a, b) {
                if (a[column] > b[column]) {
                  return -1;
                }
                if (a[column] < b[column]) {
                  return 1;
                }
                return 0;
              });              
            }else{
              this.data.sort(function (a, b) {
                if (a[column] > b[column]) {
                  return 1;
                }
                if (a[column] < b[column]) {
                  return -1;
                }
                return 0;
              });                               
            }
          },
          async getData(){
            let r = await axios.get('/'+this.route+'/get?page='+this.page)
                .then((r) => {
                    if(!r.data) return false;

                    if(r.data.error == 1){
                        console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                        return false;
                    }

                    console.log(JSON.parse(r.data.data));

                    this.data = JSON.parse(r.data.data);

                    return true;
                })
                .catch((r) => {console.log(r);return false;});

            return r;
          },     
          async searchData(){

            let l = this.loading('.list-container');
            let r = await this.ax('get','/'+this.route+'/search',{page:this.page,search:this.search,order:this.sort})
            if(!r){
              this.hideLoading(l);
              return false;
            }

            console.log(JSON.parse(r.settings).pages);

            this.data    = JSON.parse(r.data);
            this.pages   = JSON.parse(r.settings).pages;

            this.hideLoading(l);
          },
          //Search pages
          changePage(page){
            this.page = page;
            this.searchData();
          },
          doSearch(search){
            this.search = search;
            this.page = 1;
            this.searchData();
          },

          //List            
          openList(id, key){
            this.list.header = key;
            this.listData = {
              name:     this.pData.columns.filter(x => x.name == key)[0].relationMany,       
              columns:  this.pData.columns.filter(x => x.name == key)[0].list,              
              data:     this.pData.data.filter(x => x.id == id)[0][key],
              settings: this.pData.columns.filter(x => x.name == key)[0].settings,     
            }
            //Append settungs
            this.listData.settings.subList  = id;
            this.listName.relation  = {
              s:this.pData.columns.filter(x => x.name == key)[0].relationMany,
              m:this.pData.columns.filter(x => x.name == key)[0].name
            }
            $('#list-modal').modal('show');
          },
          prepareSubList(){
            $.each(this.columns, (index, val) => {
              if(val.list !== undefined){
                this.listData = true;
              }
            });
          },
          async deleteRow(){
            //Start loading
            let l = this.showLoading('#delete-modal');

            let id = this.toDelete;
            let del = await this.ax('delete','/'+this.route, {id:id});

            
            if(!del){         
              this.hideLoading(l);
              return false;
            }  

            //Success
            this.toDelete = false;
            this.data.splice(this.data.findIndex(x => x.id == id), 1);                     
            this.hideLoading(l);
            $('#delete-modal').modal('hide');   
          }, 
          detachRow(){
            let detachId = this.toDetach;
            let model = this.pSettings.subList;
            let target = this.pData.name;
            axios.delete('/'+this.route+'/detach', {data:{detachId:detachId,model:model,target:target}} )
              .then((r) => {
                  if(!r.data) return false;

                  if(r.data.error == 1){
                      console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                      return false;
                  }

                  //Success
                  this.toDetach = false;
                  this.data.splice(this.data.findIndex(x => x.id == detachId), 1);
                  $('#detach-modal').modal('hide');
              })
              .catch((r) => {console.log(r);return false;});              
          }
        }
    }
</script>


<style scooped>
  
  .bg-orange {
      background-color: orange;
  }

  .bg-attach{
    background-color: #aefe2e;
  }
  .sortable{
    cursor:pointer;
  }
</style>