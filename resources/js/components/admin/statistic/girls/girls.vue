<template>  
<div class="container-fluid">
  <h1>Statistic</h1>    
  <div class="row">   

    <!-- Menu -->  
    <div class="col-2">
      <statistic-categories></statistic-categories>
    </div>

    <!-- Content -->
    <div class="col-10 statistics-agents">
      
      <h1>Girls</h1>

      <!-- Period -->
      <div class="row">
        <div class="input-group input-group-sm mb-3">
          <!-- Period -->
          <div class="d-flex" style="align-items: center;">
            <span class="pr-2">Period:</span>  
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">From</span>
            </div>
            <datepicker  
              v-model="from"
              :monday-first="true"
            ></datepicker>
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">To</span>
            </div>
            <datepicker  
              v-model="to"
              :monday-first="true"
            ></datepicker>

            <!-- ID -->
            <div class="form-group ml-3 my-0">
              <input v-model="id" type="number" class="form-control" placeholder="id">
            </div>            
            <button @click="getGirls()" type="button" class="btn btn-primary">🔍</button>
          </div>


          
        </div>
      </div>  

      <!-- List -->
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th>Date</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Girl</th>
            <th>Clien</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <template v-for='dates in girls'>
            <template v-for='types in dates'>
              <template v-for='girlz in types'>
                <template v-for='men in girlz'>
                  <template v-for='item in men'>

                    <tr>
                      <td>{{item.item_date}}</td>
                      <td>{{item.type}}</td>
                      <td>{{men.length}}</td>
                      <td>{{item.girl}}</td>
                      <td>{{item.client}}</td>
                      <td><div @click="showLetters=false;showLetters=men" style="cursor:pointer">📄</div></td>
                    </tr>

                  </template>
                </template>
              </template>
            </template>
          </template>
        </tbody>
      </table>
    </div>   


    <!-- Letters -->
    
    <!-- Modal -->
    <div class="modal fade" id="letters-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Letters</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">

            <div v-if="showLetters != undefined && showLetters[0] != undefined">From: {{showLetters[0].letter.user.girl.name}}  To:{{showLetters[0].letter.to_user.man.name}}</div>

            <div v-for="(letter, i) in showLetters" :key="i">
              <div class="my-2">
                <div>{{letter.letter.created_at}} {{letter.letter.subject}}</div>
                <div>{{letter.letter.body}}</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {
mixins: [ mMoreAxios, mNotifications, mLoading ],
components: {Datepicker},  
data(){return{
  moment:moment,
  from:moment().subtract(1, 'months').format(),    
  to:moment().format(),      
  girls:[], 
  id:false,
  showLetters:false,
}},
async mounted() {
  this.getGirls();
},
watch: {
  showLetters: function(){
    if(this.showLetters) $('#letters-modal').modal('show');
  },
},
methods:{
  async getGirls(){
    let l = this.loading('.statistics-agents');
    let r = await this.ax('get','/admin/statistic/girls/get',{
      from:moment(this.from).unix(),
      to:moment(this.to).unix(),
      id:this.id,
    });
    if(!r){
      this.hideLoading(l);
      return false;
    }

    this.girls = r;    

    this.hideLoading(l);
  }
},

}
</script>

<style>

</style>