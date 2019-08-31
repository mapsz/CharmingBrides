 <template>
    <div class="container-fluid create">
      <form>    
        <!-- Inputs -->
        <div v-for="input in pInputs">
          <juge-input v-model="data[input.name]" :p-input="input" :p-route="route" :p-required-all="requiredAll"/>
        </div> 
        <!-- Required info -->
        <juge-required-text :p-required-all="requiredAll"/>          
        <!-- errors -->
        <juge-errors :p-errors="errors"/>
        <!-- Save button -->
        <button
          @click="addData()" 
          type="button" 
          class="btn col-12 btn-success create-buton">
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
          let l = this.showLoading('.create');

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
          $('.save-buton').show();

          //Error
          if(!r){
            this.hideLoading(l);
            return false;
          }
          //  Success
          this.hideLoading(l);
          this.$emit('createSuccess',r);
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