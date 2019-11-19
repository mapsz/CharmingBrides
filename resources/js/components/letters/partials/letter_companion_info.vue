<template>
    <div class="container-fluid">
      <div class="media">
        
        <div class="media-body align-self-center">
          <a :href="(pCompanion.man == 1) ? '/man/'+pCompanion.id : '/girl/'+pCompanion.id  " >
            <h2 class="mt-0">{{pCompanion.name}} ({{pCompanion.id}})</h2>
          </a>
            From {{pCompanion.location}}<br>
            {{pCompanion.age}} Years old
            
        </div>
        <a :href="'/girl/'+pCompanion.id" >
          <img class="mr-3" :src="'/'+pCompanion.photo[0]" style="height: 280px;">
        </a>
      </div>
    </div>
</template>

<script>
  export default {        
    mixins: [ ],
    props:['p-companion'],
    data(){
      return {
        //
      }
    }
  }
</script>
