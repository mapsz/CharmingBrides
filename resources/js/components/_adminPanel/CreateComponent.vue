 <template>
    <div class="container">
      <h1 class="modal-title pb-2 text-capitalize">
        <span v-if="prop_edit">Edit</span>
        <span v-else>Add New</span>
        <span class="text-capitalize">{{prop_name.s}}</span>
      </h1>
      <form>
        <div v-for="input in prop_inputs">
          <!-- Custom Component -->
          <div v-if="input.component !== undefined">
            <component :is="input.component" :attr="input" :propData="data"></component>
          </div>
          <!-- Radio -->
          <div v-else-if="input.type === 'radio'">
              <fieldset class="form-group">
                  <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">{{input.caption}}</legend>
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
          <!-- Select -->
          <div v-else-if="input.type === 'select'">
              <div 
                class="form-group row">
                  <label 
                    :for="[[input.name]]+'Input'" 
                    class="col-sm-2 col-form-label text-capitalize">
                      {{input.caption}}
                  </label>
                  <div class="col-sm-10">   
                    <select 
                      v-model="data[input.name]" 
                      :name="[[input.name]]" 
                      id="inputGroupSelect01" 
                      class="custom-select" 
                      >
                        <option v-if="!prop_edit_data" value="" selected="">Choose...</option> <!-- @@@перевод -->
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
          <!-- Input -->
          <div v-else>
              <div 
                class="form-group row">
                  <label 
                    :for="[[input.name]]+'Input'" 
                    class="col-sm-2 col-form-label text-capitalize">
                      {{input.caption}}
                  </label>
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
        <button v-if="prop_edit"
          @click="editData()" 
          type="button" 
          class="btn col-12 btn-warning edit">
            <span>Edit</span>
            <span class="text-capitalize">{{prop_name.s}}</span>
        </button>
        <button v-else
          @click="addData()" 
          type="button" 
          class="btn col-12 btn-success save">
            <span>Add</span>
            <span class="text-capitalize">{{prop_name.s}}</span>
        </button>

      </form>
    </div>
</template>

<script>

//@@@ при открытии и закритии модала измененые данные не сохраниются после сэйва

    export default {  
        props:['prop_inputs','prop_name','prop_edit','prop_edit_data','prop_route'],
        data(){
            return {
                data:this.setData(),
                errors:false,
            }
        },
        watch: {
          prop_edit_data: {
            deep:true,
            handler:function(){this.setValues();},
            //
          },
        },
        mounted() {
          //      
        },
        methods:{
            setData(){
              let data = {};
              //Set Output data
              $.each(this.prop_inputs, (i,input) => {  
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
                    url: '/'+this.prop_route,
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
              d.id = this.prop_edit;

              console.log(d);

              let r = await axios({
                    method: 'post',
                    url: '/'+this.prop_route,
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
              this.$emit('putSuccess',{'work':'edit','edit':r});
              //Clear fields
              this.clearFields();
              return true;  //@@@ color row
            },
            clearFields(){
              this.errors=false;  
              //unset Radios @@@        
            },
            setValues(){

               if(this.prop_edit){
                  //Set Current values
                  $.each(this.prop_edit_data, (index, val) => {
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
