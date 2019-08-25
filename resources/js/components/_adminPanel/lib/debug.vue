<script>
  export default {
    data(){return{
      debug:false,
      logErrors:true,
    }},
    mounted() {
      if(this.debug)
        console.log('debug on!');
    },
    methods: {
      dlog(message){
        if(this.debug)
          console.log(message);
      },
      derror(message){
         if(this.logErrors)
          console.log(message);       
      }
    }
  }
</script>