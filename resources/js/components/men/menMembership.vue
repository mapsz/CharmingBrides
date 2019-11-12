 <template>
    <div :id="'men-membership-'+pRow.id">

      <div class="table-caption">
        {{membership.name}}
        <button class="btn btn-sm btn-primary" @click="showMore()">More</button>
      </div>

      <!-- Membership modal -->
      <div 
        v-if="more"
        class="modal fade" 
        tabindex="-1"
        role="dialog"
        aria-hidden="true"        
        :id="'men-membership-modal-'+pRow.id"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content ">
            <div class="modal-header bg-primary">
              <h5 class="modal-title" style="color:white;">{{pRow.name}} Memberships</h5>
              <button type="button" class="close" @click="hideMore()" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Edit balance -->
              <div class="edit-balance mb-4">
                <div class="form-group mb-1">
                  <label>Edit Balance</label>
                  <small id="emailHelp" class="form-text text-muted">Current Balance: {{pRow.balance}}</small>
                  <input type="text" class="form-control" v-model="balance">
                </div>

                <button type="submit" @click="editBalance(1)" class="btn btn-success">Add</button>
                <button type="submit" @click="editBalance(0)" class="btn btn-danger">Remove</button>
              </div>
              <!-- Attach Membership -->
              <div class="men-membership-attach">     
                <div>
                  <div class="form-group row"> 
                    <div class="col-sm-3">                      
                      <!-- Header  -->
                      <h5>Attach membership:</h5>
                    </div>
                    <!-- input body -->
                    <div class="col-sm-6">   
                      <select 
                        v-model="toAddMembership" 
                        name="toAddMembership" 
                        class="custom-select" 
                        >
                          <option 
                            v-for="attribute in membershipList"
                            :value="attribute.id"                        
                            >
                              {{attribute.name}} ({{attribute.price}})
                          </option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <button type="button" class="btn btn-success btn-membership-attach" @click="attachMembership()">Attach</button>
                    </div>
                  </div>  
                </div>
              </div>              
              <!-- Membership History -->
              <div 
                v-if="membershipHistoryData && membershipHistoryData.length > 0"
                class="men-membership-history-list"
              >   
                <h5>History</h5>             
                <list-component 
                  :p-data="{columns:membershipHistoryColumns,data:membershipHistoryData}"
                />      
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="hideMore()">Close</button>
            </div>
          </div>
        </div>
      </div>

    </div>
</template>

<script>
    export default {
      data(){return{
        membership:{
          name:'',
        },
        more:false,
        //Membership history
        membershipHistoryLoaded:false,
        membershipHistoryData:[],
        membershipHistoryColumns:[],
        //Membership list
        membershipListLoaded:false,
        membershipList:[],
        //to add membership
        toAddMembership:false,
        //wait element
        waitElementTime:0,
        balance:'',
      }},
      mixins: [ mMoreAxios, mNotifications, mLoading, mDebug ],
      props:['p-row','p-attr'],
      mounted() {
        this.getCurrentMembership(this.pRow.id);
      },    
      methods:{
        showMore(){
          this.more = true;      
          this.showUnloadedModal('#men-membership-modal-'+this.pRow.id);
          if(!this.membershipHistoryLoaded){ this.getMembershipHistory(this.pRow.id);}              
          if(!this.membershipListLoaded){ this.getMembershipList();}               
        },
        hideMore(){    
          console.log('men-membership-modal-'+this.pRow.id);
          $('#men-membership-modal-'+this.pRow.id).modal('hide');     
          // this.more = false;    
        },
        async getCurrentMembership(id){
          //Show loading
          let loading = this.showLoading('#men-membership-'+this.pRow.id+' .table-caption');

          //Get membership
          let membership = await this.ax('get','/memberships/current', {user_id:id})

          // No membership
          if(!membership){
            this.membership.name = "No membership";
            this.hideLoading(loading);
            return false;
          } 

          //Set membership
          this.membership.name = membership.name;

          //Stop Loading
          this.hideLoading(loading);
        },
        async getMembershipList(){
          //Show loading
          let loading = this.showLoading('#men-membership-modal-'+this.pRow.id+' .men-membership-history-list'); //@@@ елемент появляется позже

          //Get membership list
          let membershipList = await this.ax('get','/admin/membership/get', {});

          this.membershipList = membershipList;

          this.membershipHistoryLoaded = true;

          //Stop Loading
          this.hideLoading(loading);
        },
        async attachMembership(membership_id){
          //Show loading
          let loading = this.showLoading('#men-membership-modal-'+this.pRow.id+' .men-membership-history-list'); //@@@ елемент появляется позже

          if(!this.toAddMembership){this.hideLoading(loading);return false;};

          //Attach membership
          let membershipHistory = await this.ax(
                                      'put','/admin/memberships/attach',
                                      {user_id:this.pRow.id, membership_id:this.toAddMembership},
                                      '.btn-membership-attach');

          //Update lists
          this.getMembershipHistory(this.pRow.id);
          this.getCurrentMembership(this.pRow.id);
          //Balance update @@@

          //Stop Loading
          this.hideLoading(loading);
        },
        async getMembershipHistory(id){
          //Show loading
          let loading = this.showLoading('#men-membership-modal-'+this.pRow.id+' .men-membership-history-list'); //@@@ елемент появляется позже

          //Get membership
          let membershipHistory = await this.ax('get','/memberships/history', {user_id:id})

          this.membershipHistoryColumns   = membershipHistory.columns;
          this.membershipHistoryData      = membershipHistory.data;

          //Stop Loading
          this.hideLoading(loading);
        },
        async showUnloadedModal(modal, time = 0){
          //Timeout
          if(time >= 60000) return false;

          //Element exist
          console.log($(modal).length);
          if($(modal).length > 0){
            $(modal).modal('show');
            return false;
          }
          //Launch next
          await setTimeout(() => {
            time += 500;
            this.showUnloadedModal(modal, time);
          },500)        
        },
        async editBalance(add){
          let l = this.loading('.edit-balance');
          let r = await this.ax('post','/admin/man/balance/edit',{user_id:this.pRow.id,add:add,balance:this.balance});

          if(!r){
            this.hideLoading(l);
            return
          }
          location.reload();
        }

      }
    }
</script>


<style>
  
.modal-scrollfix.modal-scrollfix {
    overflow-y: hidden;
}

</style>