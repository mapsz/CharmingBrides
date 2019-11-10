<template>
  <div class="container mt-3">
    <div class="row">
      <div class="col"><h1>Man</h1></div>
    </div>    
    <a :href="'/letters?companion='+pId">
      <button class="btn btn-primary">Send message</button>
    </a>
  </div>
</template>

<script>
    export default {
    props:['p-id'],        
    }
</script>
