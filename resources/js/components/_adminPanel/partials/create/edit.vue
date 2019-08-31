<template>
    <div class="container-fluid edit">
      <form>    
        <!-- Inputs -->
        <div v-for="input in inputs">
          <juge-input 
            v-model="data[input.name]" 
            :p-input="input" 
            :p-value="pEditData[input.name]" 
            :p-route="route" 
            :p-required-all="requiredAll"
          />
        </div> 
        <!-- Required info -->
        <juge-required-text :p-required-all="requiredAll"/>          
        <!-- errors -->
        <juge-errors :p-errors="errors"/>
        <!-- Save button -->
        <button
          @click="editData()" 
          type="button" 
          class="btn col-12 btn-success edit-buton">
            <span>Confirm</span>
        </button>
      </form>
    </div>
</template>

<script>
    export default {       
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['pInputs','pRoute','p-edit-data'],
        data(){
          return {
            route: this.pRoute['prefix'] + this.pRoute['r'], 
            data:this.setData(),
            inputs:[],
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
          this.setPasswordInputComponents();
        },
        methods: {
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
          setPasswordInputComponents(){
            let inputs = [];
            $.each(this.pInputs, (index, val) => {
              if(val.type == 'password'){
                val.component = "juge-password-edit-input";
                val.attr = this.pEditData._id;                
              } 
              if(val.type == 'password' && val.name === "confirm_password") return true;
              inputs.push(val);
            });            
            this.inputs = inputs;
          },
          async editData(){
            
            let l = this.showLoading('.edit');
            let data = {};
            $.each(this.data, (i,input) => {  
              if(input != ""){
                data[i] = input;
              }
            });
            data.id = this.pEditData['_id'];

            console.log({data:data});
            let r = await this.ax('post', '/'+this.route, data);

            Error
            if(!r){
              this.hideLoading(l);
              return false;
            }

            this.$emit('editSuccess',data.id);
            this.hideLoading(l);
          }
        }
               
    }
</script>
