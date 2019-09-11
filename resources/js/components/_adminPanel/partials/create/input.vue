<template>
    <div class="container-fluid">
      <!-- Custom Component input -->
      <div v-if="input.component !== undefined">
        <div class="form-group row">
          <!-- caption -->
          <div class="col-sm-2"> 
            <!-- required -->
            <span v-if="!requiredAll && input.required" class="required">*</span>
            <!-- label -->
            <legend class="col-form-label text-capitalize pt-0">{{input.caption}}</legend>
            <!-- input body -->
          </div>
          <!-- input body -->
          <div class="col-sm-10">    
            <component :p-attr="input.attr" :p-row="input" :p-route="pRoute" :p-value="pValue" :is="input.component" ></component>
          </div>
        </div>
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
                    v-model="value"
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
              v-model="value" 
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
              v-model="value"                
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
              v-model="value"                
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
              v-model="value"
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
              v-model="value"                
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
            <div :class="'row m-0 edit-files edit-files-'+this.pInput.name">
              <div class="col-3" v-for="file in files">
                <div class="row m-2">
                  <div v-if="fileType == 'image'" class="file-image">
                    <div class="row">
                      <div class="col"> 
                        <img   :src="'/'+assets + input.path + '/' + file" :alt="file">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <button 
                          v-if="pInput.main != undefined && pInput.main && !isMain(file)" 
                          type="button" 
                          class="btn btn-success btn-sm" 
                          @click="setMain(file)"
                        >
                          Main
                        </button>
                        <span v-if="isMain(file)" class="text-success">Main</span>
                        <button type="button" class="btn btn-danger btn-sm" @click="deleteFile(file)">Delete</button>
                      </div>
                    </div>
                  </div>
                  <div v-else class="file-text">
                    <span >{{'\\'+assets + input.path + '\\' + file}}</span>
                    <button type="button" class="btn btn-danger btn-sm" @click="deleteFile(file)">X</button>
                  </div>   
                </div>
              </div>
            </div>                 
            <file-upload-component
              :p-route="pRoute"
              :p-name="input.name"
              :p-max-file-size="input.maxFileSize"
              :p-max-file-count="input.maxFileCount"
              :p-file-type="input.fileType"
              @filesUpdated="updateFiles"
            />
          </div>  
        </div> 
      </div>   

    </div>
</template>

<script>
    export default {
        props:['pInput',"pRequiredAll", "pRoute",'pValue'],
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {            
            input: this.pInput,
            value: this.pValue,
            data:[],
            files:[],
            assets:assets
          }
        }, 
        mounted() {
            this.files = JSON.parse(JSON.stringify(this.pValue));
        },               
        computed:{
          requiredAll:function(){
            if(this.pRequiredAll == undefined) return true;
            else return this.pRequiredAll;            
          },
          fileType(){

            let t = false;

            if(typeof(this.pInput.fileType) != 'object'){
              return t;
            }       

            $.each(this.pInput.fileType, (i, v) => {
               if(v.includes('image')) t = 'image';
            });

            return t;

          }
        },
        watch: {
          value: function ($new,$old) {
              this.$emit('input',$new)
          },  
        },
        methods:{
          //Files
          updateFiles(data){
            this.value = data.files;
          },
          async deleteFile(file){

            let l = this.showLoading('.edit-files-'+this.pInput.name);
            let r = await this.ax('delete', '/'+this.pRoute+'/file/delete', {inputName:this.input.name,fileName:file});

            if(!r) {
              this.hideLoading(l);
              return;
            }

            //delete file
            let i = this.value.findIndex(x=>x == file);
            if(i > -1)
              this.value.splice(i,1);

            this.hideLoading(l);

            return;
          },
          async setMain(file){// @@@@

            let l = this.showLoading('.edit-files-'+this.pInput.name);
            let r = await this.ax('post', '/'+this.pRoute+'/file/main', {inputName:this.input.name,fileName:file});

            if(r){
               window.location.reload(); //@@@
               return;
            }

            this.hideLoading(l);

            return;            
          },
          isMain(file){ // @@@@
            return file.includes('_0.');
          }
        }        
    }
</script>

<style scooped>

  .edit-files img{
    height: 100px;
  }
  
  .required{
    color: tomato;
  }

  .filepond--item {
      width: calc(25% - .5em);
  }

</style>