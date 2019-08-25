<template>
  <!-- errors -->
  <div v-if="pErrors" class="alert alert-danger">
    <ul>
      <li v-for="error in pErrors">
        {{ error }}
      </li>
    </ul>
  </div>
</template>

<script>
    export default {
        props:['p-errors'],
        mounted() {
          //
        }
    }
</script>
