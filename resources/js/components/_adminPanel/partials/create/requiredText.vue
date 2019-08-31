<template>
  <div class="pl-3 mb-3" style="color: gray;font-style: italic;">
    <!-- symbol -->
    <span style="color:tomato;font-style: normal;">
      <span v-if="pRequiredAll">!</span>
      <span v-else>*</span>
    </span> 
    <!-- text -->
    <span>
      <span v-if="pRequiredAll">
        All fields required.
      </span>
      <span v-else>
        Indicates required fields.
      </span>  
    </span>      
  </div>   
</template>

<script>
    export default {
      props:['p-required-all'],
    }
</script>
