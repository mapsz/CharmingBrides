<template>
    <div class="container">
      <h1 class="modal-title pb-2 text-capitalize">
        <span v-if="pEditData">Edit</span>
        <span v-else>Add New</span>
        <span class="text-capitalize">{{pName.s}}</span>
      </h1>   
      <juge-edit
        v-if="pEditData"
        :p-name="pName"  
        :p-inputs="pInputs" 
        :p-route="pRoute"
        :p-edit-data="pEditData" 
        @editSuccess="editSuccess" 
      />
      <juge-create
        v-else
        :p-name="pName"  
        :p-inputs="pInputs" 
        :p-route="pRoute"
        @createSuccess="createSuccess" 
      />
    </div>
</template>

<script>
    export default {
        props:['p-name','p-inputs','p-route','p-settings','p-edit-data'],
        methods:{
          createSuccess(a){
            $('.create-buton').hide();
            window.location.replace("/"+this.pRoute['prefix'] + this.pRoute['r']+"?work=add&id="+a);
          },
          editSuccess(a){
            $('.edit-buton').hide();
            window.location.replace("/"+this.pRoute['prefix'] + this.pRoute['r']);
            // window.location.replace("/"+this.pRoute['prefix'] + this.pRoute['r']+"?work=edit&id="+a);
          }
        }
    }
</script>
