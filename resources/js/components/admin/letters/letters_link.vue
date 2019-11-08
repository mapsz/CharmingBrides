<template>
    <div class="container-fluid">
        <!-- Example -->
        <a  target="_blank" :href="'/letters/?girl=' +girl+ '&companion=' +companion ">
          <button class="btn btn-primary">More</button>
        </a>
    </div>
</template>

<script>
    export default {        
        mixins: [ ],
        props:['p-row','p-attr'],
        data(){
          return {
            girl:false,
            companion:false,
          }
        },                
        mounted() {
          if(this.pRow.user.girl){
            this.girl = this.pRow.user.girl.id;
            this.companion = this.pRow.toUser.man.id;
          }
          if(this.pRow.toUser.girl){
            this.girl = this.pRow.toUser.girl.id;
            this.companion = this.pRow.user.man.id;
          }

        }        
        
    }
</script>
