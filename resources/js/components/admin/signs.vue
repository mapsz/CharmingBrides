<template>
    <div class="admin-signs-history container-fluid" style="min-height: 100px">
      <h1>Signs</h1>
      <div >
        <table class="table">
          <thead class="bg-primary">
            <tr>
              <th scope="col">From</th>
              <th scope="col">To</th>
              <th scope="col">Agent</th>
              <th scope="col">Date</th>
              <th scope="col">Sign</th>
              <th scope="col">Letter</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="sign in signs"               
              :class="(day != sign.created_at.slice(8,10)) ? 'next' : ''"
            >

              <td>
                <a :href="/man/+sign.from.id">
                  {{sign.from.man.name}} ({{sign.from.id}})
                </a>
              </td>
              <td>
                <a :href="/girl/+sign.to.girl.id">
                  {{sign.to.girl.name}} ({{sign.to.girl.id}})
                </a>
              </td>
              <td><span v-if="sign.to.girl.agent.length > 0">{{sign.to.girl.agent[0].name}}</span></td>
              <td>{{sign.created_at}}</td>

              <td v-if="day = sign.created_at.slice(8,10)">
                <div v-if="sign.to_confirmed == 0" >
                  <button 
                    @click="doLike(sign.to_id,sign.from_id,1,sign.id)"
                    class="btn btn-primary"
                  >yes</button>
                  <button 
                    @click="doLike(sign.to_id,sign.from_id,-1,sign.id)"
                    class="btn btn-primary"
                  >no</button> 
                </div>               
                <span  v-else>Already send</span>
              </td>
              <td>
                <a :href="'/letters?girl='+sign.to_id+'&companion='+sign.from_id">
                  <button class="btn btn-primary">More</button>
                </a>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="pages > 1" class="row d-flex justify-content-center">
          <paginate        
            :page-count="pages"
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

      </div>
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        data(){
          return {
            signs:[],
            page:1,
            pages:1,
            day:'',
          }
        },              
        mounted() {
                    this.getSigns();
        },
        methods: {
          async getSigns(){
            //Show loading
            let l = this.showLoading('.admin-signs-history');

            //Get letters
            let data = await this.ax('get', '/admin/signs?page='+this.page);

            //Error            
            if(!data){
              this.hideLoading(l);
              return false;
            } 

            this.signs = data.signs;
            this.pages = data.pages;
            
            this.hideLoading(l);
          },
          async doLike(from,to,like,id){
            let l = this.loading('.like-buttons');

            let likes = await this.ax('post', '/like',{fromId:from,toId:to,like:like,a:id});

            this.hideLoading(l);

            this.getSigns();
          },          
          pageHandler(p){
            this.page = p;
            this.getSigns();
          }
        }
    }
</script>

<style scoped>
  
  .next{
    border-top: 4px #96004659 dashed;  
  }

</style>