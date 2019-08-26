<template>
    <div class="container-fluid p-0">
      <div class="list-group">
        <div 
          v-for="companion in pCompanions"
          class="user-item list-group-item list-group-item-action"
          v-bind:class="{'item-active' : pActiveCompanion.id == companion.id}"
          @click="setActive(companion)"
        >
          <div class="item-wrapper row">
            <!-- Photo -->
            <div class="img-wrapper col-4 text-center">
              <img 
                class="" 
                :src="assets+'/media/gallery/'+companion.id+'_0.jpg'" 
                :alt="companion.name"
              >
            </div>
            <!-- Name -->
            <div class="info-wrapper col-8">
              {{companion.name}} 
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {        
        props:['p-companions', 'p-active-companion'],
        data(){
          return {
            assets:assets,
          }
        },             
        methods: {
          setActive(id){
            this.$emit('set-active-companion',id);
          },
        }

    }
</script>



<style scoped>

  .user-item .img-wrapper{
    height:40px;
  }
  
  .user-item img{
    height:100%;
  }

  .user-item{
    cursor:pointer;
  }

  .item-active{
    background-color: #bf005a;
    color: white;
  }


</style>