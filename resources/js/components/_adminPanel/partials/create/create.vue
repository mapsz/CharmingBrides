 <template>
    <div class="container-fluid">
      <form>    
        <!-- Inputs -->
        <div v-for="input in pInputs">
          <juge-input v-model="data[input.name]" :p-input="input" :p-route="route" :p-required-all="requiredAll"/>
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
        <button
          @click="addData()" 
          type="button" 
          class="btn col-12 btn-success save">
            <span>Confirm</span>
        </button>
      </form>
    </div>
</template>

<script>
  export default {  
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    props:['pInputs','pRoute'],
    data(){
      return {
        route: this.pRoute['prefix'] + this.pRoute['r'], 
        data:this.setData(),
        errors:false,
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
          this.$emit('putSuccess',r);
          return true;   
        },
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