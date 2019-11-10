<template>
  <div class="media border p-3 my-4" style="background-color: white;">
    <img 
      v-if="pImages.length > 0"
      :src="'/public/img/'+pImages[0]" 
      class="align-self-center col-3 mr-3" 
    >
    <div class="media-body align-self-center">
      <div class="row">
        <div class="col-12">
          <h3 class="mt-0">{{pTitle}}</h3>
          <p
            v-for="body in pBody"
          >
            {{body}}
          </p>
          <p
            v-if="pRegards"
          >
            Regards,<br>
            Charming Brides Team.
          </p>
          <p
            v-if="pDate"
            style="
                float: right;
                font-size: 10pt;
                color: #9b9999;
            "
          >
            {{pDate}}
          </p> 
        </div>
        <div 
          v-if="pImages.length > 1"
          class="col-12"
        >
          <div class="row">
            <div v-if="i != 0" v-for="(img, i) in pImages" class="col">
              <img             
                :src="'/public/img/'+img" 
                class="col" 
                style="height:160px;width:auto;display:block;margin:auto;"
              >
            </div>
          </div>
        </div>
      </div>     
    </div>
  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-title','p-body','p-images','p-regards','p-date'],
        data(){
          return {
            //
          }
        },
    }
</script>
