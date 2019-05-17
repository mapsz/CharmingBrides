<template>
  <div class="modal" id="letterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Letter to {{pUser.name}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
              <label for="subject" class="col-sm-2 col-form-label text-capitalize">
                Subject:
              </label>
              <div class="col-sm-10">                      
                <input v-model="letter.subject" class="form-control"  id="subject" type="text" name="subject" placeholder="Enter subject">
              </div>
          </div>

          <div class="form-group">
            <label for="letter">Letter:</label>
            <textarea v-model="letter.body" class="form-control" id="letter" rows="5"></textarea>
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
        </div>
        <div class="modal-footer">
          <button @click="sendLetter()" type="button" id="btn-send" class="btn">Send</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
      props:['p-user','p-letter'],
      data(){
          return {
              showLetter:this. pLetter,
              letter:{
                subject:"",
                body:"",
              },
              errors:false,
          }
      },
      watch: {
        pLetter:function(vNew,vOld){
          if(vNew){
            $('#letterModal').modal('show');
          }
        }
      },
      mounted() {
          $('#letterModal').on('hidden.bs.modal',  (e) => {            
            this.showLetter = false;
            this.$emit('closeLetter');
          });       
      
      },
      methods:{
        async sendLetter(){

            $('#btn-send').hide();

            let r = await axios({
                    method: 'put',
                    url: '/letter',
                    data: {
                      'subject':this.letter.subject,
                      'body':this.letter.body,
                      'to_user_id':this.pUser.id
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

                    //Success                    
                    $('#letterModal').modal('hide');
                    this.letterRefresh();
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

            $('#btn-send').show();
        },
        letterRefresh(){
          this.letter = {
                subject:"",
                body:"",
              }      
        }


      }
   } 
</script>


<style scoped>
  
  #letterModal .modal-header {
    background-color: #bf005a;
    color:white;
  }

  #letterModal #btn-send{
    background-color: #bf005a;
    color:white;
  }


</style>