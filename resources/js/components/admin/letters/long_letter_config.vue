<template>
    <div class="container-fluid">
        <div 
          class="form-group row">
            <label 
              for="letterSizeInput" 
              class="col-sm-6 col-form-label text-capitalize">
                Long letter length
            </label>
            <div class="col-sm-4 p-0">                      
                 <input     
                     v-model="letterSize"                
                     type="number"                      
                     id="letterSizeInput" 
                     name="letterSizeInput" 
                     class="form-control" 
                     min="0"
                  >
            </div>
            <div class="col-sm-2">
              <button class="btn btn-primary" @click="setLenght()">Set</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){return{
            letterSize:"",
            errors:[],
          }
        },
        mounted() {
          this.getLength();
        },
        methods: {
          getLength(){
            axios({
                method: 'get',
                url: '/letter/long/length',
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

                console.log(r.data);

                //Success
                this.letterSize = r.data.length;
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
          },
          setLenght(){
           axios({
                method: 'post',
                url: '/letter/long/length',
                data: {
                  'letterSize':this.letterSize,
                },
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

                console.log(r.data);

                //Success  //@@@
                // this.letterSize = r.data.length;
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
          }
      }

    }
</script>

