 <template>
    <div class="container">

      <h1 class="modal-title pb-2 text-capitalize">
        <span v-if="pEdit">Edit</span>
        <span v-else>Add New</span>
        <span class="text-capitalize">{{pName.s}}</span>
      </h1>
      <form>    
        <div v-for="input in pInputs">
          <!-- Custom Component input -->
          <div v-if="input.component !== undefined">
            <component :is="input.component" :attr="input" :propData="data"></component>
          </div>
          <!-- Radio input -->
          <div v-else-if="input.type === 'radio'">
            <fieldset class="form-group">
              <div class="row">
                <!-- caption -->
                <div class="col-sm-2"> 
                  <!-- required -->
                  <span v-if="!requiredAll && input.required" class="required">*</span>
                  <!-- label -->
                  <legend class="col-form-label pt-0">{{input.caption}}</legend>
                  <!-- input body -->
                </div>
                <!-- input body -->
                <div class="col-sm-6">
                  <div 
                    v-for="attribute in input.attributes"
                    class="form-check">
                      <input
                        type="radio"
                        v-model="data[input.name]"
                        class="form-check-input"
                        :value="attribute.id"
                        :name="[[input.name]]"
                        :id="[[input.name]]+[[attribute.id]]+'Input'"
                      >
                      <label 
                        class="form-check-label" 
                        :for="[[input.name]]+[[attribute.id]]+'Input'">
                        <span class="text-capitalize" >
                          {{attribute.caption}}
                        </span>
                      </label>
                  </div>                    
                </div>
                <div class="col-4">
                  <!-- <button class="btn btn-primary float-right">
                      Add
                  </button>-->
                </div>
              </div>
            </fieldset>
          </div>          
          <!-- Select input -->
          <div v-else-if="input.type === 'select'">
            <div class="form-group row">
              <!-- caption -->
              <div class="col-sm-2">       
                <!-- required -->
                <span v-if="!requiredAll && input.required" class="required">*</span>
                <!-- label -->
                <legend class="col-form-label pt-0">{{input.caption}}</legend>
                <!-- input body -->
              </div>
              <!-- input body -->
              <div class="col-sm-10">   
                <select 
                  v-model="data[input.name]" 
                  :name="[[input.name]]" 
                  id="inputGroupSelect01" 
                  class="custom-select" 
                  >
                    <option v-if="!pEditData" value="" selected="">Choose...</option> <!-- @@@перевод -->
                    <option 
                      v-for="attribute in input.attributes"
                      :value="attribute.id"                        
                      :id="[[input.name]]+[[attribute.id]]+'Input'"
                      >
                        {{attribute.name}} 
                    </option>
                </select>
              </div>
            </div>  
          </div>
          <!-- Simple Input -->
          <div v-else-if="
            input.type === 'text' ||
            input.type === 'number' ||
            input.type === 'email' ||
            input.type === 'date' ||
            input.type === 'url'  "
          >
            <div  class="form-group row">
              <!-- caption -->
              <div class="col-sm-2 ">
                <!-- required -->
                <span v-if="!requiredAll && input.required" class="required">*</span>    
                <!-- label -->
                <label 
                  :for="[[input.name]]+'Input'" 
                  class="col-form-label text-capitalize"
                >
                  {{input.caption}}
                </label>
              </div>
              <!-- input body -->
              <div class="col-sm-10">                      
                <input     
                  v-model="data[input.name]"                
                  :type="[[input.type]]" 
                  :name="[[input.name]]" 
                  :value="[[input.value]]" 
                  class="form-control" 
                  :id="[[input.name]]+'Input'" 
                  :placeholder="[[input.example]]"
                >
              </div>
            </div>
          </div>
          <!-- Date input @@@ some date picker -->
          <div v-else-if="input.type === 'date'">
            <div  class="form-group row">
              <!-- caption -->
              <div class="col-sm-2 ">
                <!-- required -->
                <span v-if="!requiredAll && input.required" class="required">*</span>    
                <!-- label -->
                <label 
                  :for="[[input.name]]+'Input'" 
                  class="col-form-label text-capitalize"
                >
                  {{input.caption}}
                </label>
              </div>
              <!-- input body -->
              <div class="col-sm-10">
                <input     
                  v-model="data[input.name]"                
                  :type="[[input.type]]" 
                  :name="[[input.name]]" 
                  :value="[[input.value]]" 
                  class="form-control" 
                  :id="[[input.name]]+'Input'" 
                  :placeholder="[[input.example]]"
                >
              </div>
            </div>            
          </div>
          <!-- Textarea input-->
          <div v-else-if="input.type === 'textarea'">
            <div  class="form-group row">
              <!-- caption -->
              <div class="col-sm-2 ">
                <!-- required -->
                <span v-if="!requiredAll && input.required" class="required">*</span>    
                <!-- label -->
                <label 
                  :for="[[input.name]]+'Input'" 
                  class="col-form-label text-capitalize"
                >
                  {{input.caption}}
                </label>
              </div>
              <div class="col-sm-10">
                <textarea 
                  :rows="input.row"
                  v-model="data[input.name]"
                  :name="[[input.name]]"
                  class="form-control" 
                  :id="[[input.name]]+'Input'" 
                  :placeholder="[[input.example]]"                  
                >
                  <!-- :value="[[input.value]]"  @@@               -->
                </textarea>
              </div>
            </div>
          </div>
          <!-- Password input -->
          <div v-else-if="input.type === 'password'">
            <div  class="form-group row">
              <!-- caption -->
              <div class="col-sm-2 ">
                <!-- required -->
                <span v-if="!requiredAll && input.required" class="required">*</span>    
                <!-- label -->
                <label 
                  :for="[[input.name]]+'Input'" 
                  class="col-form-label text-capitalize"
                >
                  {{input.caption}}
                </label>
              </div>
              <!-- input body -->
              <div class="col-sm-10">                      
                <input     
                  v-model="data[input.name]"                
                  :type="[[input.type]]" 
                  :name="[[input.name]]" 
                  :value="[[input.value]]" 
                  class="form-control" 
                  :id="[[input.name]]+'Input'" 
                  :placeholder="[[input.example]]"
                >
              </div>
            </div>           
          </div>
          <!-- Files Input -->
          <div v-else-if="input.type === 'file'">
            <div  class="form-group row">
              <!-- caption -->
              <div class="col-sm-2 ">
                <!-- required -->
                <span v-if="!requiredAll && input.required" class="required">*</span>    
                <!-- label -->
                <label 
                  :for="[[input.name]]+'Input'" 
                  class="col-form-label text-capitalize"
                >
                  {{input.caption}}
                </label> 
              </div>     
              <!-- input body -->
              <div class="col-sm-10">                      
                <file-upload-component
                  :p-route="route"
                  :p-name="input.name"
                  :p-max-file-size="input.maxFileSize"
                  :p-max-file-count="input.maxFileCount"
                  :p-file-type="input.fileType"
                  :p-files="files" 
                  @filesUpdated="updateFiles"
                />
              </div>  
            </div> 
          </div>               
        </div>
        <!-- Required info -->
        <div class="pl-3 mb-3" style="color: gray;font-style: italic;">
          <!-- symbol -->
          <span style="color:tomato;font-style: normal;">
            <span v-if="requiredAll">
              !
            </span>
            <span v-else>
              *
            </span>
          </span> 
          <!-- text -->
          <span>
            <span v-if="requiredAll">
              All fields required.
            </span>
            <span v-else>
              Indicates required fields.
            </span>  
          </span>      
        </div>        
        <!-- errors -->
        <div v-show="errors" class="alert alert-danger">
            <ul>
                <li v-for="error in errors">
                    <!-- <span v-for="err in error" >                         -->
                        {{ error }}
                    <!-- </span>                 -->
                </li>
            </ul>
        </div>
        <!-- Save button -->
        <button v-if="pEdit"
          @click="editData()" 
          type="button" 
          class="btn col-12 btn-warning edit">
            <span>Edit</span>
            <span class="text-capitalize">{{pName.s}}</span>
        </button>
        <button v-else
          @click="addData()" 
          type="button" 
          class="btn col-12 btn-success save">
            <span>Add</span>
            <span class="text-capitalize">{{pName.s}}</span>
        </button>
      </form>
    </div>
</template>

<script>


    export default {  
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['pInputs','pName','pEdit','pEditData','pRoute'],
        data(){
          return {
            route: this.pRoute['prefix'] + this.pRoute['r'], 
            data:this.setData(),
            errors:false,
            files:{},
          }
        },
        computed:{
          requiredAll:function(){
            let r = true;
            $.each(this.pInputs, (index, val) => {
              if(!val['required']){
                r = false;
              }
            });
            return r;
          }
        },
        watch: {
          pEditData: {
            deep:true,
            handler:function(){this.setValues();},
            //
          },
        },
        mounted() {
          //      
        },
        methods:{
            //Files
            updateFiles(data){
              console.log(data.files);
              this.data[data.name] = data.files;
              this.files[data.name] = data.files;
            },
            //Data
            setData(){
              let data = {};
              //Set Output data
              $.each(this.pInputs, (i,input) => {  
                  data[input.name] = "";
                  // Set default attributes                  
                  if(input.attributes !== undefined){             
                    $.each(input.attributes, (j,attribute) => {
                      if(attribute.default !== undefined){
                        if(attribute.default == true){
                          data[input.name] = attribute.id;
                        }
                      }
                    });  
                  }              
              });
              return data;      
            },
            async addData(){

              //Clear errors
              this.errors = false;
              //Hide save button
              $('.save').hide();

              let r = await axios({
                    method: 'put',
                    url: '/'+this.route,
                    data: this.data,
                  })
                  .then((r) => {                    
                    //Check requst
                    if(!r.data){
                        this.errors = ['Somethink gone wrong'];                        
                        return false;
                    } 

                    //Check errors
                    if(r.data.error == 1){
                        console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                        return false;
                    }
                    //Success
                    return r.data.id;
                  })
                  .catch((error) => {   
                      if(error.response.status == 422){
                          //Validation errors
                          this.errors = error.response.data.errors;
                      }else{
                          this.errors = ['Somethink gone wrong!'];
                      }                  
                      return false;
                  });

              //Show buuton
              $('.save').show();

              //Error
              if(!r){
                return false;
              }
              //  Success
              //Send event
              this.$emit('putSuccess',{'work':'add','id':r});
              //Clear fields
              this.clearFields();
              return true;   
            },
            async editData(){

              //Clear errors
              this.errors = false;
              //Hide save button
              $('.edit').hide();

              let d = this.data;
              d.id = this.pEdit;

              console.log(d);

              let r = await axios({
                    method: 'post',
                    url: '/'+this.route,
                    data: this.data,
                  })
                  .then((r) => {
                    
                    //Check requst
                    if(!r.data){
                        this.errors = ['Somethink gone wrong'];                        
                        return false;
                    } 

                    //Check errors
                    if(r.data.error == 1){
                        console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                        return false;
                    }

                    //Success
                    return r.data.edit;
                  })
                  .catch((error) => {                      
                      if(error.response.status == 422){
                          //Validation errors
                          this.errors = error.response.data.errors;
                      }else{
                          this.errors = ['Somethink gone wrong'];
                      }                  
                      return false;
                  });

              //Show buuton
              $('.edit').show();

              //Error
              if(!r){
                return false;
              }
              //  Success
              //Send event
              this.$emit('putSuccess',{'work':'edit','edit':this.data.id});
              //Clear fields
              this.clearFields();
              return true;  //@@@ color row
            },
            clearFields(){
              this.errors=false;  
              //unset Radios @@@        
            },
            setValues(){

               if(this.pEdit){
                  //Set Current values
                  $.each(this.pEditData, (index, val) => {
                    if(index == 'id') return true;

                    //Select
                    // if(index == 'id') return true;

                    this.data[index] = val;
                  });
               }else{
                  //Clear fields
                  this.clearFields();                  
               }
            }
        }
    }
</script>


<style scooped>
  
.required{
  color: tomato;
}

.filepond--item {
    width: calc(25% - .5em);
}

</style>