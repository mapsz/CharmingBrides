<template>
  <div>
    <div class="statistic-menu">
      <a href="/admin/statistic/memberships">Membership Balance</a>
      <a href="/admin/statistic/agents">Agents</a>
      <a href="/admin/statistic/girls">Girls</a>
      <a href="/admin/statistic/services">Addit. Services</a>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style scoped>
  .statistic-menu a{
    padding:5px;
    display: block;
    text-decoration: none;
    color:black;
    border:1px solid black;
    border-bottom:0px solid black;
  }
  .statistic-menu a:last-child{
    border:1px solid black;
    border-bottom:1px solid black;
  }  
  .statistic-menu a:hover{   
    background-color: #b8b8b8;
    font-weight: 600;cursor:pointer;
  }
</style>