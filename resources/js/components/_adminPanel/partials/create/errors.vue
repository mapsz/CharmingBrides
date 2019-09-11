<template>
    <div class="container-fluid">
      <div v-show="pErrors" class="alert alert-danger">
        <ul>
          <li v-for="error in pErrors">
            {{ error }}           
          </li>
        </ul>
      </div>
    </div>
</template>

<script>
  export default {        
      props:['p-errors'],
      computed:{
      // errors:function(){
      //     $.each(this.pErrors, (index, val) =>  {
      //        // @@@
      //     });
      //   },
      // },
    },
  }

</script>
