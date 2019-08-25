<script>
  export default {
    data(){return{
      // errors:[],
    }},
    watch: {
      errors: {
        deep:true,
        handler:function(){this.showErrors();},
      },
    },
    notifications: {
      showSuccessMsg: {
        type: VueNotifications.types.success,
        title: '',
        message: ''
      },
      showInfoMsg: {
        type: VueNotifications.types.info,
        title: 'Hey you',
        message: 'Here is some info for you'
      },
      showWarnMsg: {
        type: VueNotifications.types.warn,
        title: 'Wow, man',
        message: 'That\'s the kind of warning'
      },
      showErrorMsg:{
        type: VueNotifications.types.error,
        title: '',
        message: "",
        timeout: 99999,
      }
    },           
    methods: {
      showErrors(err,code=false){

        //log code
        if(code){
          console.log(code);
          this.dlog(9999);   //@@@@
        } 

        $.each(err, (k, v) => {
          if(typeof(this.v) == "object"){
            $.each(v, (key, val) => {
              this.showErrorMsg({message:val});
            });   
          }else{
            this.showErrorMsg({message:v});
          }          
        });
      },
      showSuccess(mess){
        this.showSuccessMsg({message:mess});
      }
    }
  }
</script>