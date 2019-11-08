<template>
  <div class="modal" id="buyLetterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Read Letter</h5>
          <button type="button" class="close" @click="closePay()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Please note that to read this letter would cost {{letter.cost}} EUR?</p>
        </div>
        <errors :p-errors="errors" class="mx-3" />
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="payLetter()">OK</button>
          <button type="button" class="btn btn-secondary" @click="closePay()">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {        
        props:['p-letter'],
        data(){
          return {
            letter:this.pLetter,
            errors:false,
          }
        },
        mounted() {
            $('#buyLetterModal').modal('show');
        },
        methods: {
          closePay(){
            $('#buyLetterModal').modal('hide');
            this.$emit('closePay');
          },
          async payLetter(){
            let r = await axios({
                  method: 'put',
                  url: 'letter/pay',
                  data: {id:this.letter.id},
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
                      this.errors = [r.data.text];
                      if(this.errors.findIndex(x => x == 'Not enought balance!') >= 0)
                          window.open('/memberships','_blank');
                      return false;
                  }
                  //Success
                  this.errors = false;
                  

                  this.$emit('successPay', r.data.letter);
                  $('#buyLetterModal').modal('hide');
                  this.$emit('closePay');

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
          }
        }
    }
</script>
