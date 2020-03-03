<template>
  <div class="container-fluid">
    <h1>Statistic</h1>
    <div class="row">
      <div class="col-2">
        <statistic-categories></statistic-categories>
      </div>
      <div class="col-10 statistics-memberships">
        <h1>Memberships</h1>
        <!-- Period -->
        <div class="row">
          <div class="input-group input-group-sm mb-3">
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
            <button @click="getMemberships()" class="btn btn-sm btn-primary ml-2">Search</button>         
          </div>
        </div>       
        <!-- List -->
        <div class="row">
          <table class="table">
            <thead class="thead-light">
              <tr>
                <th>Membership</th>
                <th>Quantity</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="membership in memberships" :key="membership.id">
                <td @click="openModal(membership.id)"><a href="#">{{membership.name}}</a></td>
                <td>{{membership.user.length}}</td>
                <td>{{membership.price * membership.user.length}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>      
    </div>

    <!-- modal -->
    <div id="membership-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>   
          <div class="modal-body">       
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th>Date</th>
                  <th>Client</th>
                  <th>Price</th>
                  <th>Balance</th>
                  <th>Expire</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in cMembership.user" :key="user.id">
                  <td>{{user.pivot.created_at}}</td>
                  <td>{{user.id}} {{user.man.name}} {{user.man.surname}}</td>
                  <td>{{cMembership.price}}</td>
                  <td>{{user.man.balance}}</td>
                  <td>{{expire(user.pivot.created_at,cMembership.period)}}</td>
                </tr>
              </tbody>
            </table>
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
  components: {
    Datepicker
  },  
  data(){return{   
    from:moment().subtract(1, 'months').format(),
    to:moment().format(),
    memberships:[],
    cMembership:[],
  }},
  mounted() {
    this.getMemberships();
  },
  methods:{
    async getMemberships(){
      let l = this.loading('.statistics-memberships');
      let r = await this.ax('get','/admin/statistic/memberships/get',{
        from:moment(this.from).unix(),
        to:moment(this.to).unix()
      });
      if(!r){
        this.hideLoading(l);
        return false;
      }

      this.memberships = r;

      this.hideLoading(l);
    },
    openModal(id){
      $('#membership-modal').modal('show');
      let membership = this.memberships.find(x => x.id == id);
      this.cMembership = membership;
    },
    expire(date,period){
      return moment(date).add(period, 'day').format("DD-MM-YYYY");;
    }
  }
}
</script>

<style>

</style>