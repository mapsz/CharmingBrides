<template>
  <div v-if="pPages > 1" class="row d-flex justify-content-center">
    <paginate        
      :page-count="pPages"
      :value="page"
      :container-class="'pagination'"
      :page-class="'page-item'"
      :page-link-class="'page-link'"
      :prev-class="'page-item'"
      :prev-link-class="'page-link'"
      :next-class="'page-item'"
      :next-link-class="'page-link'"
      :click-handler="pageHandler"
    >
    </paginate>
  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-pages','p-page'],
        data(){
          return {
            page:this.pPage,
          }
        },
        mounted() {
        },        
        watch: {
          pPage: function(newVal, oldVal){
            this.page = this.pPage;
          },   
        },        
        methods: {
          getCurrentPage(){
            let qs = queryString.parse(location.search);
            let page = 1;
            if(qs.page !== undefined){
              page = parseInt(qs.page);
              this.page = page;
            }
            return page;
          },
          pageHandler(page){
            // location.replace(location.origin + location.pathname + '?page=' + page);
            this.page = page;
            this.$emit('changePage',page);
          },
        }
    }
</script>
