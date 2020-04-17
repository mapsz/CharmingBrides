<template>
  <div class="container">
    <h1 class="m-4">Favorite Girls</h1>

    <div 
      class='row'
      v-for="(girl,k) in girls"
      :key="k"
    >
      <div class="col my-3">
        <a :href="'/girl/'+girl.id">
          <img :src="'/'+girl.photo[0]" style="height:100px"><br>
          <span >{{girl.name}}({{girl.id}})</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-data'],
        data(){
          return {
            girls:JSON.parse(this.pData),
          }
        },
    }
</script>
