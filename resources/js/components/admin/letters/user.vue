<template>
    <div class="container-fluid">
      <div class="img-wrapper">
        <a :href="'/'+this.route+'?from='+this.pRow.girl.id+'&with='+this.pRow.man.id">
        {{id}}
        <img :src="assets+'/media/gallery/'+id+'_0.jpg'"  :alt="name">
        {{name}}
        </a>
      </div>
    </div>
</template>

<script>
  export default {        
    props:['p-row','p-attr'],
    data(){
      return {
        assets:assets,
        id:this.pRow[this.pAttr.man].id,
        name:this.pRow[this.pAttr.man].name,
        route:this.pAttr.route
      }
    },      
  }
</script>

<style scoped>

  .img-wrapper{
    height:40px;
  }
  
  img{
    height:100%;
  }

</style>