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
              :src="'/'+companion.photo[0]" 
              :alt="companion.name"
            >
          </div>
          <!-- Name -->
          <div class="info-wrapper" :class="companion.read ? 'col-8' : 'col-6'">
            <span class="row"><b>{{companion.name}}</b></span>
            <span class="row" style="font-size: 8pt;">{{formateDate(companion.date)}}</span>
          </div>
          <div v-if="!companion.read" class="col-2">
            <div style="
                    background-color: #ffeb3b;
                    height: 14px;
                    width: 14px;
                    border-radius: 7px;
                    border: 1px solid black;"
            >
              <span style="font-size:1px;color:#ffeb3b;">1</span>
            </div>
          </div>
        </div>
      </div>
    </div>
<!--     <paginate        
      :page-count="20"
      :container-class="'pagination'"
      :page-class="'page-item'"
      :page-link-class="'page-link'"
      :prev-class="'page-item'"
      :prev-link-class="'page-link'"
      :next-class="'page-item'"
      :next-link-class="'page-link'"
    >
    </paginate> make pagination-->
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
          formateDate(date){
            return moment(date).format('lll');
          }
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