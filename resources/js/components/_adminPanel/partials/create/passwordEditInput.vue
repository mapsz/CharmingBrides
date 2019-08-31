<template>
    <div class="container-fluid p-0">
      <!-- Small modal -->
      <button 
        type="button" 
        class="btn btn-primary" 
        data-toggle="modal" 
        data-target="#juge-password-modal"
      >
        Change Password
      </button>

      <span v-if="success" class="text-success pl-2">Password successfully changed</span>

      <div 
        class="modal fade" 
        id="juge-password-modal"
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="mySmallModalLabel" 
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content juge-password-change">
            <div class="modal-header">
              <b>Change Password</b>
            </div>
            <div class="modal-body">
              <!-- New Password -->
              <div  class="form-group row">
                <!-- caption -->
                <div class="col-sm-4 ">
                  <!-- label -->
                  <label 
                    for="juge-password-input" 
                    class="col-form-label text-capitalize"
                  >
                    New Password
                  </label>
                </div>
                <!-- input body -->
                <div class="col-sm-8">                      
                  <input     
                    v-model="data.password"                
                    type="password" 
                    name="password"
                    class="form-control" 
                    id="juge-password-input" 
                  >
                </div>
              </div> 
              <!-- Repeat Password -->
              <div  class="form-group row">
                <!-- caption -->
                <div class="col-sm-4 ">
                  <!-- label -->
                  <label 
                    for="juge-confirm-password-input" 
                    class="col-form-label text-capitalize"
                  >
                    Repeat Password
                  </label>
                </div>
                <!-- input body -->
                <div class="col-sm-8">                      
                  <input     
                    v-model="data.confirm_password"                
                    type="password" 
                    name="confirm_password"
                    class="form-control" 
                    id="juge-confirm-password-input" 
                  >
                </div>
              </div> 
              <!-- Generated Password -->
              <div v-if="generatedPassword" class="form-group row">
                <!-- caption -->
                <div class="col-sm-4 ">
                  <!-- label -->
                  <label 
                    for="juge-confirm-password-input" 
                    class="col-form-label text-capitalize"
                  >
                    Generated Password
                  </label>
                </div>
                <!-- input body -->
                <div class="col-sm-8">                      
                  {{generatedPassword}}
                </div>
              </div>               
              <div  class="row">
                <div class="col-12">
                  <button @click="generatePassword()" type="button" class="m-auto d-block btn btn-primary">Generate password</button>
                </div>
              </div>                                
            </div>
            <div class="modal-footer">              
              <button type="button" class="btn btn-success" @click="changePassword()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import generator from 'generate-password';

    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row','p-attr','p-route'],
        data(){
          return {
            data:{
              id:this.pAttr,
              password:'',
              confirm_password:'',
            },
            generator:generator,
            generatedPassword:false,
            success:false,
          }
        },
        computed:{
          requiredAll:function(){
            //
          },
        },
        watch: {
          pEditData: {
            deep:true,
            handler:function(){this.setValues();},
            //
          },
          question: function (newQuestion, oldQuestion) {
            this.answer = 'Waiting for you to stop typing...'
            this.debouncedGetAnswer()
          }      
        },                
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
          async generatePassword(){
            let ps = this.generator.generate({
                length: 8,
                numbers: true
            });

            this.data.password        = ps;
            this.data.confirm_password    = ps;
            this.generatedPassword  = ps;
          },
          async changePassword(){

            let l = this.showLoading('.juge-password-change');
            let r = await this.ax('post', '/'+this.pRoute, this.data);

            //Error
            if(!r){
              this.hideLoading(l);
              return false;
            }

            //Refresh passwords
            this.data.password        = "";
            this.data.confirm_password    = "";
            this.generatedPassword  = "";     

            //Set success text  
            this.success = true;

            //Exit modal
            $('#juge-password-modal').modal('hide');    


            this.hideLoading(l);
          }
        }
    }
</script>
