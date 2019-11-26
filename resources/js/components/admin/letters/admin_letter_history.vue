<template>
    <div class="admin-letter-history container-fluid" style="min-height: 100px">
      <h1>Letters</h1>

      <!-- Search Mailer -->
      <div class="row justify-content-center mx-4">
        <juge-search
          :p-search="search"
          @doSearch="doSearch"
        ></juge-search>   
      </div>      

      <!-- List -->
      <div>
        <table class="table">
          <thead class="bg-primary">
            <tr>
              <th scope="col">From</th>
              <th scope="col">To</th>
              <th scope="col">Subject</th>
              <th scope="col">Answer</th>
              <th scope="col">Agent</th>
              <th scope="col">Date</th>
              <th scope="col">More</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="(letter, i) in letters"               
              :class="setDay(i) ? 'next' : ''"
            >
              <td>
                <a :href="/man/+letter.user.id">{{letter.user.man.name}} {{letter.user.man.surname}}({{letter.user.id}})</a>
              </td>
              <td>
                <a :href="/girl/+letter.to_user.id">{{letter.to_user.girl.name}}({{letter.to_user.id}})</a>
              </td>
              <td>{{letter.subject.slice(0, 30)}}</td>
              <td>
                <div v-for="(answer,k) in letter.answers">
                  {{k+1}}){{answer.body.slice(0, 30)}}...
                </div>                
              </td>
              <td>
                <div v-if="letter.to_user.girl.agent.length > 0">
                  {{letter.to_user.girl.agent[0].name}}
                </div>
              </td>
              <td>{{formateDate(letter.created_at)}}</td>
              <td>
                <a :href="'/letters?girl='+letter.to_user.id+'&companion='+letter.user.id">
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
        props:['p-data'],
        data(){
          return {
            letters:[],
            day:'',
            page:1,
            pages:0,
            search:JSON.parse(this.pData).search,
            toSearch:{},
          }
        },           
        mounted() {
          //
        },
        methods: {
          setDay(i){     
            if(i == 0) return false;
            if(this.letters[i-1].created_at.slice(8,10) != this.letters[i].created_at.slice(8,10)) return true
            else return false;
          },
          async getLetters(){
            //Show loading
            let l = this.showLoading('.admin-letter-history');

            //Get letters
            let data = await this.ax('get', '/admin/letter/history?page='+this.page, {search:this.toSearch});

            //Error            
            if(!data){
              this.hideLoading(l);
              return false;
            } 

            this.letters = data.letters;            
            this.pages = data.pages;
            this.page = 1;
            
            this.hideLoading(l);
          },
          pageHandler(p){
            this.page = p;
            this.getLetters();
          },
          doSearch(search){
            this.pages = 1; //Trigger
            this.toSearch = search;
            this.getLetters();
          },
          formateDate(date){
            return moment(date).format('DD MMM Y hh:mm')
          }
        }
    }
</script>


<style scoped>
  
  .next{
    border-top: 3px #96004659 dashed;  
  }

</style>