<template>
    <div :class='"container-fluid admin-girl-confirm-"+pRow.id'>
        <!-- Example -->
        <span v-if="confirm" style="color:limegreen">
          Confirmed
          <button class="btn btn-sm btn-danger" @click="doConfirm(0)"> </button>
        </span>
        <span v-else style="color:tomato">
          Unconfirmed
          <button class="btn btn-sm btn-success" @click="doConfirm(1)"> </button>
        </span>
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row','p-attr'],
        data(){
          return {
              //Data
              confirm:this.pRow.confirm,
          }
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
