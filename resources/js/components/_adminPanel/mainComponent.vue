<template>
    <div class="container">
      <h1 class="text-capitalize">{{pName.m}}</h1>
      <div v-if="pSettings.add" class="mb-1">
        <a :href="pName.s+'/create'">
          <button type="button" class="btn btn-success">Add new</button>
        </a>
      </div>
      <list-component 
        :p-data = "pData"
        :p-settings = "pSettings"
        :p-route = "pRoute"
        :p-name = "pName"
      />
    

      <!-- Edit modal -->
      <div 
        v-if="pSettings.edit"
        class="modal fade" id="edit-modal"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header" v-bind:class="{ 'bg-warning': edit, 'bg-success':(!edit)}">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="container-fluid">
                    <admin-panel-create-wrapper-component
                      :p-edit="edit"
                      :p-inputs="pInputs" 
                      :p-name="pName"
                      :p-edit-data = "editData"
                      :p-route = "pRoute"
                    />                     
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
        </div>
      </div>
      

    </div>

</template>

<script>
    export default {
        props:['p-data','p-name','p-inputs','p-route','p-settings'],
        data(){
            return {
                data:[],
                columns:[],
                edit:false,
                editData:this.formEditdata(),
                recentAdd:false,
                recentEdit:false,
            }
        },
        mounted() {
          this.data = this.pData.data;
          this.columns = this.pData.columns;          
        },
        methods:{  
          editRow(rowId){
            //Set edit Id
            this.edit = rowId;
            //Set edit Data
            //Get row    
            let data = this.data.filter(x => x.id == rowId)[0];
            data = Object.assign(data);
            //Refresh data
            // @@@надо ли?
            // Set data
            $.each(this.pInputs, (index, val) => {
              //List data
              if(val.type == 'radio' || val.type == 'select'){                  
                let attr = val.attributes.filter(x => x['name'] == data[val.name])[0];
                if(attr){
                  this.edit_data[val.name] = attr.id;
                }else{
                  this.edit_data[val.name] = null;
                }
              }
              //Single data
              else{                
                this.edit_data[val.name] = data[val.name];
              }

              //Remove objects
              if(typeof(data[val.name]) == 'object' && data[val.name] != null){
                this.edit_data[val.name] = data[val.name]['attr'];
              }

              //Remove nulls
              if(data[val.name] == null){
                this.edit_data[val.name] = "";
              }

            });

            //Show modal
            $('#edit-modal').modal('show');
          },
          formEditdata(){
            let d = {};

            $.each(this.pInputs, function(index, val){
              d[val.name] = "";
            });

            return d;
          },    
        }
    }
</script>
