<template>
    <div :class='"container-fluid admin-girl-confirm-"+pRow.id'>
        <!-- Example -->
        <span v-if="confirm">
          <button class="btn btn-sm btn-success border" @click="doConfirm(0)"> </button>
        </span>
        <span v-else>
          <button class="btn btn-sm btn-danger border" @click="doConfirm(1)" > </button>
        </span>
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row'],
        data(){
          return {
              //Data
              confirm:this.pRow.confirm,
          }
        },
        mounted() {
          this.confirm=this.pRow.confirm;
        },
        watch: {
          pRow: {
            deep:true,
            handler:function(){this.confirm=this.pRow.confirm;},
          },
        },
        methods: {
          async doConfirm(c){
            let l = this.showLoading('.admin-girl-confirm-'+this.pRow.id);
            let r = await this.ax('post','/admin/girls/confirm', {'id':this.pRow.id,'confirm':this.confirm});
            if(r)
              this.confirm = c;

            this.hideLoading(l);
          }
        }
    }
</script>

<style scoped>
  
  button{
    width: 20px;
    height: 20px;
    border-radius: 10px; 
  }

</style>