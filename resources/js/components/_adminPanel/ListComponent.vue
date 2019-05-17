<!-- VEHICLE LIST -->

<template>
    <div class="container">
        <div class="">
            <h1 class="text-capitalize">{{prop_name.m}}</h1>
            <div v-if="prop_settings.add" class="mb-1">
              <a :href="prop_name.s+'/create'">
                <button type="button" class="btn btn-success">Add new</button>
              </a>
            </div>
            <table class="table table-striped">
              <thead class="bg-primary text-white">
                <!-- Captions -->
                <tr>
                  <!-- Columns -->
                  <th v-for="column in columns" scope="col" class="text-capitalize" style="font-size:16pt">
                    {{column.caption}}
                  </th>
                  <!-- Edit -->
                  <th scope="col" v-if="prop_settings.edit" style="font-size:16pt">Edit</th>
                  <!-- Delete -->
                  <th scope="col" v-if="prop_settings.delete" style="font-size:16pt">Delete</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data -->
                <tr v-for='row in data' 
                  :id="'row'+row.id" 
                  :class="{'bg-success':(recent_add == row.id),'bg-warning':(recent_edit == row.id) }"
                >
                  <!-- Columns -->
                  <td v-for="key in columns">
                    <!-- Custom components -->
                    <span v-if="typeof(row[key.name]) == 'object' && row[key.name] != null">
                      <component :is="row[key.name].component" :attr="row[key.name].attr"></component>
                    </span>
                    <!-- default value -->
                    <span v-else class="caption">{{row[key.name]}}</span>
                  </td>
                  <!-- Edit -->
                  <td v-if="prop_settings.edit" class="float">
                    <button @click="editRow(row.id)" type="button" class="btn btn-warning border border-primary">Edit</button>
                  </td>
                  <!-- Delete -->
                  <td v-if="prop_settings.delete" class="float">
                    <button data-toggle="modal" data-target="#delete-modal" @click="toDelete=row.id" type="button" class="btn btn-danger border border-primary">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
        </div>

        <!-- Edit modal -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                        :prop_edit="edit"
                        :prop_inputs="prop_inputs" 
                        :prop_name="prop_name"
                        :prop_edit_data = "edit_data"
                        @putSuccess="putSuccess"
                      />                     
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
          </div>
        </div>
        
        <!-- Delete modal -->
        <div class="modal" id="delete-modal" tabindex="-1" role="dialog">
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
    </div>
</template>

<script>
    export default {
        props:['prop_data','prop_name','prop_inputs','prop_route','prop_settings'],
        data(){
            return {
                data:[],
                columns:[],
                edit:false,
                toDelete:false,
                edit_data:this.formEditdata(),
                recent_add:false,
                recent_edit:false,
            }
        },
        mounted() {
          this.data = this.prop_data.data;
          this.columns = this.prop_data.columns;
          this.recentAdd();
        },
        methods:{  
          recentAdd(){
            var vars = [], hash;

            if (window.location.href.indexOf('?') < 0){
              return false;
            }

            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }            

            if(vars.add !== 'undefined'){
              this.putSuccess(vars);
            }else{
              return false;
            }
          },
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
            $.each(this.prop_inputs, (index, val) => {
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
            $('#modal').modal('show');
          },
          formEditdata(){
            let d = {};

            $.each(this.prop_inputs, function(index, val){
              d[val.name] = "";
            });

            return d;
          },
          deleteRow(){
            let id = this.toDelete;
            axios.delete('/'+this.prop_route, {data:{id:id}})
                .then((r) => {
                    if(!r.data) return false;

                    if(r.data.error == 1){
                        console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                        return false;
                    }
                    this.data.splice(this.data.findIndex(x => x.id == id), 1);
                    $('#delete-modal').modal('hide');
                })
                .catch((r) => {console.log(r);return false;});            
          },
          async putSuccess(edit){
            //Refresh edit
            this.edit = false;
            //Hide modal
            $('#modal').modal('hide');
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
              this.recent_add = edit.id;
            }else{
              this.recent_edit = edit.edit.id;
            }            
          },
          async getData(){
            this.data = [];
            let r = await axios.get('/'+this.prop_route+'/get')
                .then((r) => {
                    if(!r.data) return false;

                    if(r.data.error == 1){
                        console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                        return false;
                    }

                    this.data = JSON.parse(r.data.data).data;

                    return true;
                })
                .catch((r) => {console.log(r);return false;});

            return r;
          },         
        }
    }
</script>
