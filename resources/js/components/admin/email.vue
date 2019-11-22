<template>
  <div class="container-fluid email-sender">
  
    <h1>Send Email Letters</h1>

    <div class="row">
      <div class="col-6">

        <label for="subject">Subject:</label>
        <input class="w-100 mb-4" id="subject "type="text" v-model="subject">
        
        <vue-editor v-model="content"></vue-editor>

        <button @click="sendEmails()" class="btn btn-success mt-3">Send Emails</button>

      </div>

      <div class="col-6 men-container">
        <div v-if="searchMenShow" class="container-fluid py-3 border  men-search-container">
          <h3 class="text-center">To Men</h3>
          <!-- Search -->
          <div class="row m-0 p-0">
            <juge-search
              :p-search="JSON.parse(pData).manSearch"
              :p-param-route="'/parametrs/men'"
              @doSearch="menSearch"
            ></juge-search>
          </div>
          <!-- Serach List -->
          <div class="row m-0 p-0">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">Add</th>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Age</th>
                  <th scope="col">Created At</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="men in searchMen">
                  <th>
                    <fa-icon 
                    @click="addToList(men.id)" 
                    icon="plus" 
                    class="pl-1 text-success"
                    style="cursor:pointer"/>
                  </th>
                  <th>{{men.id}}</th>
                  <td>{{men.name}}{{(men.surname)?' '+men.surname:""}}</td>
                  <td>{{men.birth}}</td>
                  <td>{{men.created_at}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- Paginator -->      
          <pages :p-page="menPage" :p-pages="menPages" @changePage="manChangePage"></pages>  
          <!-- add button -->
          <button @click="addFromSearchMen()" class="btn btn-sm btn-success"><fa-icon icon="plus" class="pl-1"/> 
            Add all from search list
          </button>
          <button @click="addAllMen()" class="btn btn-sm ml-2 btn-primary"><fa-icon icon="plus" class="pl-1"/>
            Add all Men
          </button>
        </div>
        <div class="container-fluid py-3 border">
          <div class="row m-0 p-0">
            <h3>To List</h3>
          </div>
          <div class="row m-0 p-0">
            <span v-for="u in toList"> 
              {{u.name}}({{u.id}}).
            </span>
            <span v-if="toAllMen">
              <b>ALL MEN SELECTED</b>
            </span>
          </div>          
          <div class="row m-0 p-0">
            <b class="col-12">Total Men:{{(toListTotal) ? toListTotal : toList.length}}</b>
            <button @click="removeListMen()" class="btn btn-sm btn-danger"><fa-icon icon="plus" class="pl-1"/> Remove</button>
          </div>
        </div>
      </div> 
    </div>   
  </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
 
  export default {
    components: {
      VueEditor
    },     
    mixins: [ mMoreAxios, mNotifications, mLoading ],
    props:['p-Data'],
    data(){
      return {
        subject:"",
        content:"",
        searchMen:[],
        fromList:[],
        toList:[],
        //search        
        manSearchSettings:[],
        //pages
        menPage:1,        
        menPages:1,        
        //Add ALL
        toAllMen:false,
        toListTotal:false,
        searchMenShow:true,
      }
    },      
    computed:{
      requiredAll:function(){
        //
      },
    },
    methods: {
      async removeListMen(){
        this.toList = [];
        this.toListTotal = 0;
        this.searchMenShow=true;
        this.toAllMen=false;
      },      
      async getRecentMen(){
        let r = await this.ax('get','/admin/mailer/recent/men');
        if(r) this.searchMen = r['data'];
      },
      addFromList(id){
        if(this.fromList.find(x=>x.id == id)) {
          this.showErrors(id+' alredy listed',false,2500);
          return;
        }

        let add = this.searchGirls.find(x=>x.id == id);
        this.fromList.push(add);
      },
      addToList(id){
        if(this.toList.find(x=>x.id == id)) {
          this.showErrors(id+' alredy listed',false,2500);
          return;
        }
        let add = this.searchMen.find(x=>x.id == id);
        this.toList.push(add);
      },      
      async addAllMen(){
        let l = this.loading('.men-container');
        let r = await this.ax('get','/admin/mailer/men/count');

        if(!r) {this.hideLoading(l); return;}

        //Remove search
        this.searchMenShow = false;

        //Show total
        this.toListTotal = r;
        //Update to List
        this.toList = [];
        //Set to all
        this.toAllMen = true;

        this.hideLoading(l);
      },      
      addFromSearchMen(){
        $.each(this.searchMen, (i, v) => {
          this.addToList(v.id);
        });
      },
      menSearch(search){
        this.manSearchSettings = search;
        this.menPage = 1;
        this.getMen();
      },
      manChangePage(page){
        this.menPage =page;
        this.getMen();
      },
      async getMen(){
        let l = this.loading('.men-container');
        let r = await this.ax('get','/admin/man/search',{page:this.menPage,search:this.manSearchSettings})
        if(!r){
          this.hideLoading(l);
          return false;
        }

        this.searchMen = JSON.parse(r.data);
        this.menPages = JSON.parse(r.settings).pages;

        this.hideLoading(l);
      },
      async sendEmails(){
        let l = this.loading('.email-sender');
        if(this.toList.length < 1) this.showErrors(['Select Men!'])

        let r = await this.ax('put','/admin/email',{content:this.content,subject:this.subject,list:this.toList , all:this.toAllMen,all:this.toAllMen});

        if(!r){
          this.hideLoading(l);
          return;

        } 

        this.showSuccess('Mails Sent!');
      }
    }
  }
</script>
