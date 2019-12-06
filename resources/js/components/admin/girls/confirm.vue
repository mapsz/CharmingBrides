<template>
    <div :class='"container-fluid admin-girl-confirm-"+pRow.id'>
        <!-- Confirm -->
        <span v-if="confirm">
          <button class="btn btn-sm btn-success border" @click="doConfirm(0)"></button>
        </span>
        <span v-else>
          <button class="btn btn-sm btn-danger border" @click="doConfirm(1)" ></button>
        </span>

        <!-- Hidden -->
        <span v-if="hidden">
          <button class="btn btn-sm btn-danger border" @click="doHide(0)"> </button>
        </span>
        <span v-else>
          <button class="btn btn-sm btn-info border" @click="doHide(1)" > </button>
        </span>        
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row','p-attr','p-index'],
        data(){
          return {
              //Data
              confirm:this.pRow.confirm,
              hidden:1,
          }
        },
        mounted() {
          this.confirm=this.pRow.confirm;
          // setTimeout(this.getHide(), (300*this.pIndex));          
        },
        watch: {
          pRow: {
            deep:true,
            handler:function(){
              this.confirm=this.pRow.confirm;
              // let l = this.showLoading('.admin-girl-confirm-'+this.pRow.id);
              setTimeout(()=>{this.getHide()}, (300*this.pIndex));
            },
          },
        },
        methods: {
          async doConfirm(c){
            //No agent
            if(this.pAttr < 4) return;
            let l = this.showLoading('.admin-girl-confirm-'+this.pRow.id);
            let r = await this.ax('post','/admin/girls/confirm', {'id':this.pRow.id,'confirm':this.confirm});
            if(r)
              this.confirm = c;

            this.hideLoading(l);
          },
          async doHide(c){
            let l = this.showLoading('.admin-girl-confirm-'+this.pRow.id);
            let r = await this.ax('post','/admin/girls/hidden', {'id':this.pRow.id,'hidden':c});
            if(r)
              this.hidden = c;
            else
              return;

            this.hideLoading(l);            
          },
          async getHide(){
            let l = this.showLoading('.admin-girl-confirm-'+this.pRow.id);
            let r = await this.ax('get','/admin/girls/hidden', {'id':this.pRow.id});            
            if(r)
              this.hidden = r.hide;
            else
              return;

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