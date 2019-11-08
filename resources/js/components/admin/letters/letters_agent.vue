<template>
    <div class="container-fluid">
        {{agent}}
    </div>
</template>

<script>
    export default {        
        mixins: [ ],
        props:['p-row','p-attr'],
        data(){
          return {
            agent:"--",
          }
        },       
        mounted() {
          if(this.pRow.user.girl && this.pRow.user.girl.agent && this.pRow.user.girl.agent.length > 0){
            this.agent = this.pRow.user.girl.agent[0].name;
          }
          if(this.pRow.toUser.girl && this.pRow.toUser.girl.agent && this.pRow.toUser.girl.agent.length > 0){
            this.agent = this.pRow.toUser.girl.agent[0].name;
          }

        }
    }
</script>
