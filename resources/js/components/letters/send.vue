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
              showLetter:false,
              letter:{
                subject:"",
                body:"",
              }
          }
      },
      watch: {
        pLetter:function(vNew,vOld){
          console.log('gg');
          if(vNew){
            $('#letterModal').modal('show');
          }
        }
      },
      mounted() {
          this.showLetter = this.pLetter;
          $('#letterModal').on('hidden.bs.modal',  (e) => {            
            this.pLetter = false; //@@@@
          });       
      
      },
      methods:{
        sendLetter(){
          console.log(555);
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