<template>
    <div class="container">
      <h1 class="modal-title pb-2 text-capitalize">
        <span v-if="pEdit">Edit</span>
        <span v-else>Add New</span>
        <span class="text-capitalize">{{pName.s}}</span>
      </h1>   
      <juge-create
        :p-name="pName"  
        :p-inputs="pInputs" 
        :p-route="pRoute" 
        :p-edit="pEdit" 
        :p-edit_data="pEditData" 
        @putSuccess="putSuccess" 
      />
    </div>
</template>

<script>
    export default {
        props:['pInputs','pName','pEdit','pEditData','pRoute'],
        mounted() {
            // console.log('Component mounted.')
        },
        methods:{
            putSuccess(a){

                // if(a.work == 'add')
                  window.location.replace("/"+this.pRoute['prefix'] + this.pRoute['r']+"?work=add&id="+a);
                // else{
                //   this.$emit('putSuccess',{'work':'edit','edit':a.edit});
                // }
            }
        }
    }
</script>
