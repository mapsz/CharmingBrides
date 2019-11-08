<template>
    <div class="container-fluid">
        {{info.name}}({{user.id}})
    </div>
</template>

<script>
    export default {        
        mixins: [ ],
        props:['p-row','p-attr'],
        data(){
          return {
            user:[],
            info:[],
          }
        },                
        mounted() {
          if(this.pAttr == 'from'){
            this.user = this.pRow.user;
          }
          if(this.pAttr == 'to'){
            this.user = this.pRow.toUser;
          }

          if(this.user.girl){
            this.info = this.user.girl;
          }
          if(this.user.man){
            this.info = this.user.man;
          }
        }
    }
</script>
